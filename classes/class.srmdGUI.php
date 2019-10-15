<?php

use SRAG\ILIAS\Plugins\MetaData\Field\Field;
use SRAG\ILIAS\Plugins\MetaData\Field\UserField;
use SRAG\ILIAS\Plugins\MetaData\Form\FormAdapter;
use SRAG\ILIAS\Plugins\MetaData\Form\ilObjectMapping;

require_once('./Services/Form/classes/class.ilPropertyFormGUI.php');
require_once('./Services/Object/classes/class.ilObjectListGUIFactory.php');

/**
 * Class srmdGUI
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 *
 * @ilCtrl_IsCalledBy srmdGUI: ilUIPluginRouterGUI
 */
class srmdGUI
{
    const CMD_USER_AUTOCOMPLETE = "userAutoComplete";

    /**
     * @var ilObjectMapping
     */
    protected $mapping;

    /**
     * @var int
     */
    protected $obj_id;

    /**
     * @var ilTabsGUI
     */
    protected $tabs;

    /**
     * @var ilCtrl
     */
    protected $ctrl;

    /**
     * @var ilAccessHandler
     */
    protected $access;

    /**
     * @var ilLanguage
     */
    protected $lng;

	/**
	 * @var ilTemplate
	 */
    protected $tpl;

	/**
	 * @var ilObjUser
	 */
    protected $user;

    /**
     * @var ilObject
     */
    protected $object;

    public function __construct()
    {
        global $ilCtrl, $ilTabs, $ilAccess, $lng, $tpl, $ilUser;

        $this->ctrl = $ilCtrl;
        $this->tabs = $ilTabs;
        $this->access = $ilAccess;
        $this->lng = $lng;
        $this->tpl = $tpl;
        $this->user = $ilUser;
        $this->tpl->getStandardTemplate();
    }


    public function executeCommand()
    {
        $this->checkAccess();
        $this->ctrl->saveParameter($this, 'mapping_obj_id');
        $this->ctrl->saveParameter($this, 'mapping_id');
        $this->ctrl->saveParameter($this, 'ref_id');
        $this->ctrl->saveParameter($this, 'back_target');
        $this->mapping = ilObjectMapping::findOrFail((int) $_GET['mapping_id']);
        $this->obj_id = (int) $_GET['mapping_obj_id'];
        $this->object = ilObjectFactory::getInstanceByObjId($this->obj_id);
        $this->fakeObjectHeader();
        $this->tabs->addTab('srmd_' . $this->mapping->getId(), $this->mapping->getTabTitle(), $this->ctrl->getLinkTarget($this));
        $cmd = $this->ctrl->getCmd('show');
        $this->$cmd();
        $this->tpl->show();
    }


    protected function show()
    {
        $form = $this->initForm();
        $adapter = new FormAdapter($form, new \SRAG\ILIAS\Plugins\MetaData\Object\ilConsumerObject($this->object), $this->user->getLanguage());
        foreach ($this->mapping->getFieldGroups() as $group) {
            $adapter->addFields($group);
        }
        $this->tpl->setContent($form->getHTML());
    }


    protected function save()
    {
        $form = $this->initForm();
        $adapter = new FormAdapter($form, new \SRAG\ILIAS\Plugins\MetaData\Object\ilConsumerObject($this->object), $this->user->getLanguage());
        foreach ($this->mapping->getFieldGroups() as $group) {
            $adapter->addFields($group);
        }
        if ($form->checkInput()) {
            if ($adapter->saveRecords()) {
                ilUtil::sendSuccess($this->lng->txt('saved_successfully'), true);
                $this->ctrl->redirect($this);
            } else {
                $errors = array_map(function($error) {
                    return $error->record->getField()->getLabel() . ': ' . $error->exception->getMessage();
                }, $adapter->getErrors());
                ilUtil::sendFailure('Error(s) during saving metadata:<br> ' . implode('<br>', $errors));
            }
        }
        $form->setValuesByPost();
        $this->tpl->setContent($form->getHTML());
    }

    protected function cancel()
    {
        $this->redirectBack();
    }


    /**
     * @return ilPropertyFormGUI
     */
    protected function initForm()
    {
        $form = new ilPropertyFormGUI();
        $form->addCommandButton('save', $this->lng->txt('save'));
        $form->addCommandButton('cancel', $this->lng->txt('cancel'));
        $form->setFormAction($this->ctrl->getFormAction($this));
        return $form;
    }


    /**
     * Add object header and breadcrumbs
     */
    protected function fakeObjectHeader() {
        global $ilLocator;
        $this->tpl->setTitle($this->object->getPresentationTitle());
        $this->tpl->setDescription($this->object->getLongDescription());
        $this->tpl->setTitleIcon(ilObject::_getIcon("", "big", $this->object->getType()), $this->lng->txt("obj_" . $this->object->getType()));
        $this->ctrl->setParameterByClass('ilrepositorygui', 'ref_id', (int) $_GET['ref_id']);
        $this->tabs->setBackTarget($this->lng->txt('back'), $this->ctrl->getLinkTarget($this, 'redirectBack'));
        include_once './Services/Object/classes/class.ilObjectListGUIFactory.php';
        $lgui = ilObjectListGUIFactory::_getListGUIByType($this->object->getType());
        $lgui->initItem((int) $_GET['ref_id'], $this->object->getId());
        $this->tpl->setAlertProperties($lgui->getAlertProperties());
        $ilLocator->addRepositoryItems();
        $this->tpl->setLocator();
    }

    protected function redirectBack()
    {
        if (!isset($_GET['back_target'])) {
            return;
        }
        $target = urldecode(base64_decode($_GET['back_target']));
        ilUtil::redirect($target);
    }


    protected function checkAccess()
    {
        if (isset($_GET['ref_id']) && !$this->access->checkAccess('write', '', (int) $_GET['ref_id'])) {
            ilUtil::sendInfo($this->lng->txt('permission_denied'), true);
            $this->ctrl->redirectByClass('ilpersonaldesktopgui');
        }
    }


    /**
     *
     */
    protected function userAutoComplete() {
        $field = Field::findOrFail(intval(filter_input(INPUT_GET, "field_id")));

        if (!($field instanceof UserField) || $field->options()->isOnlyDisplay()) {
            throw new InvalidArgumentException("Field need to be type " . UserField::class);
        }

        $auto = new ilUserAutoComplete();
        $auto->setSearchFields(["login", "firstname", "lastname", "email", "usr_id"]);
        $auto->setMoreLinkAvailable(true);
        $auto->setResultField("usr_id");

        if (filter_input(INPUT_GET, "fetchall")) {
            $auto->setLimit(ilUserAutoComplete::MAX_ENTRIES);
        }

        echo $auto->getList(filter_input(INPUT_GET, "term"));

        exit;
    }
}