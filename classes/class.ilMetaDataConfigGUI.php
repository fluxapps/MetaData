<?php
use SRAG\ILIAS\Plugins\MetaData\Config\ilFieldGroupFormGUI;
use SRAG\ILIAS\Plugins\MetaData\Config\ilObjectMappingFormGUI;
use SRAG\ILIAS\Plugins\MetaData\Config\SimpleTable;
use SRAG\ILIAS\Plugins\MetaData\Config\ilFieldFormGUI;
use SRAG\ILIAS\Plugins\MetaData\Field\Field;
use SRAG\ILIAS\Plugins\MetaData\Field\FieldData;
use SRAG\ILIAS\Plugins\MetaData\Field\FieldGroup;
use SRAG\ILIAS\Plugins\MetaData\Field\NullField;
use SRAG\ILIAS\Plugins\MetaData\Form\FormAdapter;
use SRAG\ILIAS\Plugins\MetaData\Form\ilObjectMapping;
use SRAG\ILIAS\Plugins\MetaData\Language\ilLanguage;
use SRAG\ILIAS\Plugins\MetaData\Object\ilConsumerObject;

require_once('./Services/Component/classes/class.ilPluginConfigGUI.php');
require_once('./Services/Form/classes/class.ilPropertyFormGUI.php');
require_once('./Modules/Course/classes/class.ilObjCourse.php');



/**
 * Class ilMetaDataConfigGUI
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 */
class ilMetaDataConfigGUI extends ilPluginConfigGUI
{

    /**
     * @var ilMetaDataPlugin
     */
    protected $pl;
    /**
     * @var ilCtrl
     */
    protected $ctrl;
    /**
     * @var ilTemplate
     */
    protected $tpl;

    /**
     * @var ilTabsGUI
     */
    protected $tabs;

    /**
     * @var ilToolbarGUI
     */
    protected $toolbar;
    /**
     * @var ilLanguage
     */
    protected $language;

    public function __construct()
    {
        global $ilCtrl, $tpl, $ilTabs, $ilToolbar;
        $this->pl = ilMetaDataPlugin::getInstance();
        $this->ctrl = $ilCtrl;
        $this->tpl = $tpl;
        $this->tabs = $ilTabs;
        $this->toolbar = $ilToolbar;
        $this->language = new ilLanguage();
    }


    /**
     * @param string $cmd
     * @throws ilException
     */
    public function performCommand($cmd)
    {
        if (!method_exists($this, $cmd)) {
            throw new \ilException("Command $cmd does not exist");
        }
        $this->$cmd();
    }

    protected function configure()
    {
        $this->listFields();
    }

    protected function listFields()
    {
        $this->addTabs('fields');
        $button = ilLinkButton::getInstance();
        $button->setCaption('Add Field', false);
        $button->setUrl($this->ctrl->getLinkTarget($this, 'createField'));
        $this->toolbar->addButtonInstance($button);
        $table = new SimpleTable(array(
            'Identifier',
            'Label',
            'Type',
            'Formatters',
            'Actions',
        ));
        /** @var Field $field */
        foreach (NullField::orderBy('class')->orderBy('identifier')->get() as $field) {
            $this->ctrl->setParameter($this, 'field_id', $field->getId());
            $url = $this->ctrl->getLinkTarget($this, 'editField');
            $this->ctrl->clearParameters($this);
            $actions = "<a href='{$url}'>Edit</a>";
            $type = str_replace('SRAG\\ILIAS\\Plugins\\MetaData\\Field\\', '', $field->getClass());
            $formatters = array_map(function ($class) {
                return str_replace('SRAG\\ILIAS\\Plugins\\MetaData\\Formatter\\', '', $class);
            }, $field->getFormatters());
            $table->row(array(
                $field->getIdentifier(),
                $field->getlabel(),
                $type,
                implode(', ', $formatters),
                $actions,
            ));
        }
        $this->tpl->setContent($table->render());
    }

    protected function createField()
    {
        $this->addTabs('fields');
        $form = new ilFieldFormGUI(new NullField(), $this->language);
        $this->tpl->setContent($form->getHTML());
    }

    protected function editField(Field $field = null)
    {
        $this->addTabs('fields');
        $field = ($field) ? $field : Field::findOrFail((int)$_GET['field_id']);
        $form = new ilFieldFormGUI($field, $this->language);
        $this->tpl->setContent($form->getHTML());
    }

    protected function saveField()
    {
        $form = new ilFieldFormGUI(new NullField(), $this->language);
        $is_new = (isset($_POST['field_id']) && !$_POST['field_id']);
        if ($form->checkInput()) {
            $class = $form->getInput('type');
            /** @var Field $field */
            $field = new $class($is_new ? 0 : (int) $_POST['field_id']);
            $field->setIdentifier($form->getInput('identifier'));
            $field->setClass($class);
            $field->setInputfieldClass($form->getInput('inputfield'));
            $field->setFormatters($form->getInput('formatters'));
            foreach ($this->language->getAvailableLanguages() as $lang) {
                $field->setLabel($form->getInput("label_$lang"), $lang);
                $field->setDescription($form->getInput("description_$lang"), $lang);
            }
            // Options
            foreach ($field->options()->getData() as $property => $value) {
                $setter = 'set' . ucfirst($property);
                if (!method_exists($field->options(), $setter)) {
                    // Maybe a field option was removed in the class but still exists in DB, skip it!
                    continue;
                }
                $field->options()->$setter($form->getInput('option_' . $property));
            }
            try {
                $field->save();
                $this->saveFieldData($field, $form);
                if ($is_new) {
                    $this->ctrl->setParameter($this, 'field_id', $field->getId());
                    ilUtil::sendSuccess('Created Field ' . $field->getLabel() . '. Please set desired Inputfield and field options', true);
                    $this->ctrl->redirect($this, 'editField');
                }
                ilUtil::sendSuccess('Saved Field ' . $field->getLabel(), true);
                $this->ctrl->redirect($this, 'listFields');
            } catch (Exception $e) {
                ilUtil::sendFailure($e->getMessage());
                $form->setValuesByPost();
                $this->tpl->setContent($form->getHTML());
            }
        }
        $form->setValuesByPost();
        $this->tpl->setContent($form->getHTML());
    }

    protected function saveFieldData(Field $field, ilPropertyFormGUI $form)
    {
        // Existing Data
        foreach ($field->getData() as $field_data) {
            $value = $form->getInput('field_data_' . $field_data->getId());
            $values = json_decode($value, true);
            // Could not parse to json -> may be one string in the default language
            $values = ($values === null) ? array($this->language->getDefaultLanguage() => $value) : $values;
            $field_data->setValues($values);
            $field_data->save();
        }
        // New Data
        if (!$form->getInput('add_data')) {
            return;
        }
        $values = explode("\n", $form->getInput('add_data'));
        foreach ($values as $value) {
            $value = str_replace("\r", '', $value);
            if (!$value) {
                continue;
            }
            $value = json_decode($value, true);
            // Could not parse to json -> may be one string in the default language
            $value = ($value === null) ? array($this->language->getDefaultLanguage() => $value) : $value;
            $field_data = new FieldData();
            $field_data->setFieldId($field->getId());
            $field_data->setValues($value);
            $field_data->save();
        }
    }


    protected function listFieldGroups()
    {
        $this->addTabs('field_groups');
        $button = ilLinkButton::getInstance();
        $button->setCaption('Add Field Group', false);
        $button->setUrl($this->ctrl->getLinkTarget($this, 'createFieldGroup'));
        $this->toolbar->addButtonInstance($button);
        $table = new SimpleTable(array(
            'Identifier',
            'Title',
            'Description',
            'Fields',
            'Actions',
        ));
        foreach (FieldGroup::orderBy('identifier')->get() as $group) {
            $this->ctrl->setParameter($this, 'field_group_id', $group->getId());
            $url = $this->ctrl->getLinkTarget($this, 'editFieldGroup');
            $this->ctrl->clearParameters($this);
            $actions = "<a href='{$url}'>Edit</a>";
            /** @var FieldGroup $group */
            $fields = $group->getFields();
            $field_labels = array_map(function($field) { return $field->getIdentifier(); }, $fields);
            $table->row(array(
                $group->getIdentifier(),
                $group->getTitle(),
                $group->getDescription(),
                implode(', ', $field_labels),
                $actions,
            ));
        }
        $this->tpl->setContent($table->render());
    }

    protected function createFieldGroup()
    {
        $this->addTabs('field_groups');
        $form = new ilFieldGroupFormGUI(new FieldGroup(), $this->language);
        $this->tpl->setContent($form->getHTML());
    }

    protected function editFieldGroup()
    {
        $this->addTabs('field_groups');
        /** @var FieldGroup $group */
        $group = FieldGroup::findOrFail((int) $_GET['field_group_id']);
        $form = new ilFieldGroupFormGUI($group, $this->language);
        $this->tpl->setContent($form->getHTML());
    }

    protected function saveFieldGroup()
    {
//        var_dump($_POST);die();
        $this->addTabs('field_groups');
        $form = new ilFieldGroupFormGUI(new FieldGroup(), $this->language);
        if ($form->checkInput()) {
            $group = new FieldGroup(isset($_POST['field_group_id']) ? (int) $_POST['field_group_id'] : 0);
            $group->setIdentifier($form->getInput('identifier'));
            foreach ($this->language->getAvailableLanguages() as $lang) {
                $group->setTitle($form->getInput("title_$lang"), $lang);
                $group->setDescription($form->getInput("description_$lang"), $lang);
            }
            $group->setFieldIds($form->getInput('fields'));
            try {
                $group->save();
                ilUtil::sendSuccess('Saved Field Group ' . $group->getTitle(), true);
                $this->ctrl->redirect($this, 'listFieldGroups');
            } catch (Exception $e) {
                ilUtil::sendFailure($e->getMessage());
                $form->setValuesByPost();
                $this->tpl->setContent($form->getHTML());
            }
        }
        $form->setValuesByPost();
        $this->tpl->setContent($form->getHTML());
    }

    protected function cancelFieldGroup()
    {
        $this->listFieldGroups();
    }

    protected function cancelField()
    {
        $this->listFields();
    }


    protected function createObjectMapping()
    {
        $this->addTabs('object_mapping');
        $form = new ilObjectMappingFormGUI(new ilObjectMapping(), $this->language);
        $this->tpl->setContent($form->getHTML());
    }

    protected function editObjectMapping()
    {
        $this->addTabs('object_mapping');
        $form = new ilObjectMappingFormGUI(ilObjectMapping::findOrFail((int) $_GET['object_mapping_id']), $this->language);
        $this->tpl->setContent($form->getHTML());
    }


    protected function listObjectMappings()
    {
        $this->addTabs('object_mapping');
        $button = ilLinkButton::getInstance();
        $button->setCaption('Add Mapping', false);
        $button->setUrl($this->ctrl->getLinkTarget($this, 'createObjectMapping'));
        $this->toolbar->addButtonInstance($button);
        $table = new SimpleTable(array(
            'Object Type',
            'Tab Title',
            'Active',
            'Field Groups',
            'Actions',
        ));
        foreach (ilObjectMapping::orderBy('obj_type')->get() as $mapping){
            /** @var $mapping ilObjectMapping */
            $this->ctrl->setParameter($this, 'object_mapping_id', $mapping->getId());
            $url = $this->ctrl->getLinkTarget($this, 'editObjectMapping');
            $this->ctrl->clearParameters($this);
            $actions = "<a href='{$url}'>Edit</a>";
            $table->row(array(
                $mapping->getObjType(),
                $mapping->getTabTitle(),
                $mapping->getActive(),
                implode(', ', $mapping->getFieldGroupIds()),
                $actions,
            ));
        }
        $this->tpl->setContent($table->render());
    }

    protected function saveObjectMapping()
    {
        $this->addTabs('object_mapping');
        $form = new ilObjectMappingFormGUI(new ilObjectMapping(), $this->language);
        if ($form->checkInput()) {
            /** @var ilObjectMapping $mapping */
            $mapping = ilObjectMapping::findOrGetInstance($form->getInput('object_mapping_id'));
            $mapping->setObjType($form->getInput('obj_type'));
            $mapping->setActive($form->getInput('active'));
            foreach ($this->language->getAvailableLanguages() as $lang) {
                $mapping->setTabTitle($form->getInput('tab_title_' . $lang), $lang);
            }
            $mapping->setFieldGroupIds($form->getInput('field_group_ids'));
            try {
                $mapping->save();
                ilUtil::sendSuccess('Saved Object Mapping', true);
                $this->ctrl->redirect($this, 'listObjectMappings');
            } catch (Exception $e) {
                ilUtil::sendFailure($e->getMessage());
                $form->setValuesByPost();
                $this->tpl->setContent($form->getHTML());
            }
        }
        $form->setValuesByPost();
        $this->tpl->setContent($form->getHTML());
    }


    protected function addTabs($active = '')
    {
        $this->tabs->addTab('fields', 'Fields', $this->ctrl->getLinkTarget($this, 'listFields'));
        $this->tabs->addTab('field_groups', 'Field Groups', $this->ctrl->getLinkTarget($this, 'listFieldGroups'));
        $this->tabs->addTab('object_mapping', 'ILIAS Objects Mapping', $this->ctrl->getLinkTarget($this, 'listObjectMappings'));
        if ($active) {
            $this->tabs->setTabActive($active);
        }
    }

}