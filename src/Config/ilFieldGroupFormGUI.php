<?php
namespace SRAG\ILIAS\Plugins\MetaData\Config;

require_once('./Services/Form/classes/class.ilPropertyFormGUI.php');

use SRAG\ILIAS\Plugins\MetaData\Field\Field;
use SRAG\ILIAS\Plugins\MetaData\Field\FieldGroup;
use SRAG\ILIAS\Plugins\MetaData\Field\NullField;
use SRAG\ILIAS\Plugins\MetaData\FormProperty\ilAsmSelectInputGUI;
use SRAG\ILIAS\Plugins\MetaData\Language\Language;

/**
 * Class ilFieldGroupFormGUI
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 */
class ilFieldGroupFormGUI extends \ilPropertyFormGUI
{
    /**
     * @var FieldGroup
     */
    protected $group;

    /**
     * @var Language
     */
    protected $lang;

    public function __construct(FieldGroup $group, Language $lang)
    {
        parent::__construct();
        $this->group = $group;
        $this->lang = $lang;
        $this->init();
    }

    protected function init()
    {
        global $ilCtrl;

        $this->setFormAction($ilCtrl->getFormActionByClass('ilMetaDataConfigGUI'));
        $this->addGeneral();
        $this->addFields();
        $this->addCommandButton('saveFieldGroup', 'Save');
        $this->addCommandButton('cancelFieldGroup', 'Cancel');
    }

    protected function addGeneral()
    {
        $header = new \ilFormSectionHeaderGUI();
        $header->setTitle('General');
        $this->addItem($header);

        $item = new \ilHiddenInputGUI('field_group_id');
        $item->setValue($this->group->getId());
        $this->addItem($item);

        $item = new \ilTextInputGUI('Identifier', 'identifier');
        $item->setRequired(true);
        $item->setInfo('A unique Identifier to retrieve this field group by a name - besides the ID');
        $item->setValue($this->group->getIdentifier());
        $this->addItem($item);

        foreach ($this->lang->getAvailableLanguages() as $lang) {
            $item = new \ilTextInputGUI("Title $lang", "title_$lang");
            $item->setRequired($this->lang->getDefaultLanguage() == $lang);
            $item->setValue($this->group->getTitle($lang));
            $this->addItem($item);
            $item = new \ilTextAreaInputGUI("Description $lang", "description_$lang");
            $item->setValue($this->group->getDescription($lang));
            $this->addItem($item);
        }
    }

    protected function addFields()
    {
        $options = array();
        /** @var Field $field */
        foreach (NullField::get() as $field) {
            $options[$field->getId()] = $field->getLabel(). ' [' . $field->getIdentifier() . ']';
        }
        $item = new ilAsmSelectInputGUI('Fields', 'fields');
        $item->setOptions($options);
        $item->setRequired(true);
        $item->setInfo('Select the fields belonging to this group');
        $item->setValue($this->group->getFieldIds());
        $this->addItem($item);
    }
}