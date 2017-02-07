<?php
namespace SRAG\ILIAS\Plugins\MetaData\Config;

require_once('./Services/Form/classes/class.ilPropertyFormGUI.php');

use SRAG\ILIAS\Plugins\MetaData\Field\Field;
use SRAG\ILIAS\Plugins\MetaData\Field\FieldGroup;
use SRAG\ILIAS\Plugins\MetaData\Field\NullField;
use SRAG\ILIAS\Plugins\MetaData\Form\ilObjectMapping;
use SRAG\ILIAS\Plugins\MetaData\FormProperty\ilAsmSelectInputGUI;
use SRAG\ILIAS\Plugins\MetaData\Language\Language;

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
        parent::ilPropertyFormGUI();
        $this->mapping = $mapping;
        $this->language = $language;
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
        $item->setValue($this->mapping->getObjType());
        $item->setRequired(true);
        $this->addItem($item);

        $item = new \ilCheckboxInputGUI('Active', 'active');
        $item->setChecked($this->mapping->getActive());
        $this->addItem($item);

        foreach ($this->language->getAvailableLanguages() as $lang) {
            $item = new \ilTextInputGUI('Tab Title ' . strtoupper($lang), 'tab_title_' . $lang);
            $item->setValue($this->mapping->getTabTitle($lang));
            $item->setRequired($lang == $this->language->getDefaultLanguage());
            $this->addItem($item);
        }

        $options = array();
        /** @var FieldGroup $group */
        foreach (FieldGroup::get() as $group) {
            $options[$group->getId()] = $group->getTitle(). ' [' . $group->getIdentifier() . ']';
        }
        $item = new ilAsmSelectInputGUI('Field Groups', 'field_group_ids');
        $item->setOptions($options);
        $item->setRequired(true);
        $item->setValue($this->mapping->getFieldGroupIds());
        $this->addItem($item);
    }
}