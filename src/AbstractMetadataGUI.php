<?php

namespace SRAG\ILIAS\Plugins\MetaData;

use ilFormSectionHeaderGUI;
use ilMetaDataPlugin;
use ilNonEditableValueGUI;
use ilPropertyFormGUI;
use ilUtil;
use InvalidArgumentException;
use srag\CustomInputGUIs\MetaData\MultiSelectSearchNewInputGUI\ObjectChildrenAjaxAutoCompleteCtrl;
use srag\CustomInputGUIs\MetaData\MultiSelectSearchNewInputGUI\UsersAjaxAutoCompleteCtrl;
use srag\DIC\MetaData\DICTrait;
use SRAG\ILIAS\Plugins\MetaData\Field\Field;
use SRAG\ILIAS\Plugins\MetaData\Field\OrgUnitField;
use SRAG\ILIAS\Plugins\MetaData\Field\OrgUnitsField;
use SRAG\ILIAS\Plugins\MetaData\Field\UserField;
use SRAG\ILIAS\Plugins\MetaData\Form\Error;
use SRAG\ILIAS\Plugins\MetaData\Form\FormAdapter;
use SRAG\ILIAS\Plugins\MetaData\Form\ilObjectMapping;
use SRAG\ILIAS\Plugins\MetaData\Object\ConsumerObject;

/**
 * Class AbstractMetadataGUI
 *
 * @package SRAG\ILIAS\Plugins\MetaData
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 */
abstract class AbstractMetadataGUI
{

    use DICTrait;
    const PLUGIN_CLASS_NAME = ilMetaDataPlugin::class;
    const CMD_SHOW = "show";
    const CMD_SAVE = "save";
    const CMD_BACK = "back";
    /**
     * @var ConsumerObject[]
     */
    protected $objects = [];
    /**
     * @var ilObjectMapping[]
     */
    protected $objects_mapping = [];


    /**
     * MetadataGUI constructor
     *
     * @param ConsumerObject[]  $objects
     * @param ilObjectMapping[] $object_mappings
     */
    public function __construct(array $objects, array $object_mappings)
    {
        $this->objects = $objects;
        $this->objects_mapping = $object_mappings;
    }


    /**
     *
     */
    public function executeCommand()
    {
        if (!$this->checkAccess()) {
            ilUtil::sendInfo(self::dic()->language()->txt("permission_denied"), true);

            //self::dic()->ctrl()->redirect($this, self::CMD_BACK);
            $this->back();
        }

        $this->setTabs();

        $next_class = self::dic()->ctrl()->getNextClass($this);

        switch (strtolower($next_class)) {
            case strtolower(UsersAjaxAutoCompleteCtrl::class):
                $field = Field::findOrFail(filter_input(INPUT_GET, "field_id"));

                if (!($field instanceof UserField) || $field->options()->isOnlyDisplay()) {
                    throw new InvalidArgumentException("Field need to be type " . UserField::class);
                }

                self::dic()->ctrl()->forwardCommand(new UsersAjaxAutoCompleteCtrl());
                break;

            case strtolower(ObjectChildrenAjaxAutoCompleteCtrl::class):
                $field = Field::findOrFail(filter_input(INPUT_GET, "field_id"));

                if (!($field instanceof OrgUnitField || $field instanceof OrgUnitsField) || $field->options()->isOnlyDisplay()) {
                    throw new InvalidArgumentException("Field need to be type " . OrgUnitsField::class);
                }

                self::dic()->ctrl()->forwardCommand(new ObjectChildrenAjaxAutoCompleteCtrl("orgu", $field->options()->getOrgUnitParentRefId()));
                break;

            default:
                $cmd = self::dic()->ctrl()->getCmd(self::CMD_SHOW);

                switch ($cmd) {
                    case self::CMD_SHOW:
                    case self::CMD_SAVE:
                    case self::CMD_BACK:
                        $this->{$cmd}();
                        break;

                    default:
                        break;
                }
                break;
        }
    }


    /**
     * @return bool
     */
    protected abstract function checkAccess() : bool;


    /**
     *
     */
    protected abstract function back();


    /**
     *
     */
    protected function setTabs()
    {
        self::dic()->tabs()->setBackTarget(self::dic()->language()->txt('back'), self::dic()->ctrl()
            ->getLinkTarget($this, self::CMD_BACK));

        self::dic()->tabs()->addTab('srmd_' . current($this->objects_mapping)->getId(), current($this->objects_mapping)->getTabTitle(), self::dic()->ctrl()->getLinkTarget($this, self::CMD_SHOW));
    }


    /**
     *
     */
    protected function show()
    {
        $form = $this->getForm();
        $this->getAdapters($form);

        self::output()->output($form, true);
    }


    /**
     * @return ilPropertyFormGUI
     */
    protected function getForm() : ilPropertyFormGUI
    {
        $form = new ilPropertyFormGUI();

        $form->addCommandButton(self::CMD_SAVE, self::dic()->language()->txt('save'));
        $form->addCommandButton(self::CMD_BACK, self::dic()->language()->txt('cancel'));

        $form->setFormAction(self::dic()->ctrl()->getFormAction($this));

        return $form;
    }


    /**
     * @param ilPropertyFormGUI $form
     *
     * @return FormAdapter[]
     */
    protected function getAdapters(ilPropertyFormGUI $form) : array
    {
        $adapters = [];

        foreach ($this->objects as $object) {
            $groups = [];

            $adapter = new FormAdapter($form, $object, self::dic()->user()->getLanguage());

            foreach ($this->objects_mapping as $object_mapping) {
                if (!MetadataService::getInstance()->canBeShow($object, $object_mapping, MetadataService::SHOW_CONTEXT_EDIT_IN_TAB)) {
                    continue;
                }

                foreach ($object_mapping->getFieldGroups() as $group) {
                    $groups[] = $group;
                }
            }

            $should_add_object_header = (count($this->objects) > 1 && count($groups) > 0);

            if ($should_add_object_header) {
                $object_header = new ilFormSectionHeaderGUI();
                $object_header->setTitle(self::dic()->language()->txt("obj_" . $object->getType()) . " : " . self::dic()->objDataCache()->lookupTitle($object->getId()));
                $form->addItem($object_header);
            }

            foreach ($groups as $group) {
                $adapter->addFields($group);
            }

            if ($should_add_object_header) {
                $form->addItem(new ilNonEditableValueGUI()); // Separator
            }

            $adapters[] = $adapter;
        }

        return $adapters;
    }


    /**
     *
     */
    protected function save()
    {
        $form = $this->getForm();
        $adapters = $this->getAdapters($form);

        $form->setValuesByPost();

        if ($form->checkInput()) {
            $errors = [];

            foreach ($adapters as $adapter) {
                if (!$adapter->saveRecords()) {
                    $errors = array_merge($errors, $adapter->getErrors());
                }
            }

            if (count($errors) === 0) {
                ilUtil::sendSuccess(self::dic()->language()->txt('saved_successfully'), true);

                self::dic()->ctrl()->redirect($this, self::CMD_SHOW);
            } else {
                $errors = array_map(function (Error $error) {
                    return $error->record->getField()->getLabel() . ': ' . $error->exception->getMessage();
                }, $errors);

                ilUtil::sendFailure('Error(s) during saving metadata:<br> ' . implode('<br>', $errors));
            }
        }

        self::output()->output($form, true);
    }
}
