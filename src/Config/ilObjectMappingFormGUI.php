<?php
namespace SRAG\ILIAS\Plugins\MetaData\Config;

require_once('./Services/Form/classes/class.ilPropertyFormGUI.php');

use ilRepositorySelector2InputGUI;
use SRAG\ILIAS\Plugins\MetaData\Field\Field;
use SRAG\ILIAS\Plugins\MetaData\Field\FieldGroup;
use SRAG\ILIAS\Plugins\MetaData\Field\NullField;
use SRAG\ILIAS\Plugins\MetaData\Form\ilObjectMapping;
use SRAG\ILIAS\Plugins\MetaData\FormProperty\ilAsmSelectInputGUI;
use SRAG\ILIAS\Plugins\MetaData\Language\Language;
use ilCheckboxInputGUI;

/**
 * Class ilObjectMappingFormGUI
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 */
class ilObjectMappingFormGUI extends \ilPropertyFormGUI
{
    /**
     * @var ilObjectMapping
     */
    protected $mapping;

    /**
     * @var Language
     */
    protected $language;

    public function __construct(ilObjectMapping $mapping, Language $language)
    {
        parent::__construct();
        $this->mapping = $mapping;
        $this->language = $language;
        $this->setTitle(($mapping->getId()) ? "Edit Mapping: " . $mapping->getTabTitle() : "Add new Mapping");
        $this->init();
    }

    protected function init()
    {
        global $ilCtrl;
        $this->setFormAction($ilCtrl->getFormActionByClass('ilMetaDataConfigGUI'));
        $this->addFields();
        $this->addCommandButton('saveObjectMapping', 'Save');
        $this->addCommandButton('cancelObjectMapping', 'Cancel');
    }


    protected function addFields()
    {
        $item = new \ilHiddenInputGUI('object_mapping_id');
        $item->setValue($this->mapping->getId());
        $this->addItem($item);

        $item = new \ilTextInputGUI('Object Type', 'obj_type');
        $item->setInfo('The ILIAS object type where metadata should be editable and/or displayed');
        $item->setValue($this->mapping->getObjType());
        $item->setRequired(true);
        $this->addItem($item);

        $options = array();
        /** @var FieldGroup $group */
        foreach (FieldGroup::get() as $group) {
            $options[$group->getId()] = $group->getTitle(). ' [' . $group->getIdentifier() . ']';
        }
        $item = new ilAsmSelectInputGUI('Field Groups', 'field_group_ids');
        $item->setInfo('Select the Field Groups containing the fields you want to map to objects of the type defined above');
        $item->setOptions($options);
        $item->setRequired(true);
        $item->setValue($this->mapping->getFieldGroupIds());
        $this->addItem($item);

        $item = new \ilCheckboxInputGUI('Active', 'active');
        $item->setInfo('Only active mappings are processed by the plugin');
        $item->setChecked($this->mapping->isActive());
        $this->addItem($item);

        $editable = new \ilCheckboxInputGUI('Editable', 'editable');
        $editable->setInfo('Check to add a new tab showing a form to edit the metadata');
        $editable->setChecked($this->mapping->isEditable());
        $this->addItem($editable);

        foreach ($this->language->getAvailableLanguages() as $lang) {
            $item = new \ilTextInputGUI('Tab Title ' . strtoupper($lang), 'tab_title_' . $lang);
            $item->setValue($this->mapping->getTabTitle($lang, false));
            $editable->addSubItem($item);
        }

        $header = new \ilFormSectionHeaderGUI();
        $header->setTitle('Presentation Settings');
        $this->addItem($header);

        $show_block = new \ilCheckboxInputGUI('Display in Block', 'show_block');
        $show_block->setInfo('Check to display a set of fields in a block in the right column of the object, e.g. in the content section of a course. One block per field group.');
        $show_block->setChecked($this->mapping->isShowBlock());
        $this->addItem($show_block);

        $options = array();
        foreach ($group->getFields() as $field) {
            $options[$field->getId()] = $field->getLabel() . ' [' . $field->getIdentifier() . ']';
        }

        foreach ($this->mapping->getFieldGroups() as $group) {
            $item = new ilAsmSelectInputGUI($group->getTitle(), 'show_block_group_' . $group->getId());
            $options = array();
            foreach ($group->getFields() as $field) {
                $options[$field->getId()] = $field->getLabel() . ' [' . $field->getIdentifier() . ']';
            }
            $item->setOptions($options);
            $item->setValue($this->mapping->getShowBlockFieldIds($group->getId()));
            $show_block->addSubItem($item);
        }

        $show_info = new \ilCheckboxInputGUI('Display on Info Screen', 'show_info_screen');
        $show_info->setInfo('Check to display a set of fields on the info screen of an object.');
        $show_info->setChecked($this->mapping->isShowInfoScreen());
        $this->addItem($show_info);

        foreach ($this->mapping->getFieldGroups() as $group) {
            $item = new ilAsmSelectInputGUI($group->getTitle(), 'show_info_group_' . $group->getId());
            $item->setOptions($options);
            $item->setValue($this->mapping->getShowInfoFieldIds($group->getId()));
            $show_info->addSubItem($item);
        }

        $only_certain_places = new ilCheckboxInputGUI('Only show in certain places', 'only_certain_places');
        $only_certain_places->setChecked($this->mapping->isOnlyCertainPlaces());
        $this->addItem($only_certain_places);

        $only_certain_places_ref_id = new ilRepositorySelector2InputGUI('Parent Object', 'only_certain_places_ref_id');
        $only_certain_places_ref_id->getExplorerGUI()->setSelectableTypes(["root", "cat", "grp", "fold"]);
        $only_certain_places_ref_id->setValue($this->mapping->getOnlyCertainPlacesRefId());
        $only_certain_places->addSubItem($only_certain_places_ref_id);

        $only_certain_places_whole_tree = new ilCheckboxInputGUI('Whole tree', 'only_certain_places_whole_tree');
        $only_certain_places_whole_tree->setValue($this->mapping->isOnlyCertainPlacesWholeTree());
        $only_certain_places->addSubItem($only_certain_places_whole_tree);
    }
}