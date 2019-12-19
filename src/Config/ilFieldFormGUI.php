<?php
namespace SRAG\ILIAS\Plugins\MetaData\Config;

require_once('./Services/Form/classes/class.ilPropertyFormGUI.php');

use ilCustomInputGUI;
use ilMetaDataConfigGUI;
use ilMetaDataPlugin;
use srag\CustomInputGUIs\MetaData\Waiter\Waiter;
use srag\DIC\MetaData\DICTrait;
use SRAG\ILIAS\Plugins\MetaData\Field\Field;
use SRAG\ILIAS\Plugins\MetaData\Field\OrgUnitField;
use SRAG\ILIAS\Plugins\MetaData\Field\OrgUnitsField;
use SRAG\ILIAS\Plugins\MetaData\Field\UserField;
use SRAG\ILIAS\Plugins\MetaData\Formatter\ObjectTitleFormatter;
use SRAG\ILIAS\Plugins\MetaData\FormProperty\ilAsmSelectInputGUI;
use SRAG\ILIAS\Plugins\MetaData\Language\Language;

/**
 * Class ilFieldFormGUI
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 */
class ilFieldFormGUI extends \ilPropertyFormGUI
{
    use DICTrait;
    const PLUGIN_CLASS_NAME = ilMetaDataPlugin::class;
    /**
     * @var Field
     */
    protected $field;

    /**
     * @var Language
     */
    protected $language;

    /**
     * @param Field $field
     * @param Language $language
     */
    public function __construct(Field $field, Language $language)
    {
        parent::__construct();
        $this->field = $field;
        $this->language = $language;
        $this->init();
    }

    protected function init()
    {
        global $ilCtrl;

        $this->setFormAction($ilCtrl->getFormActionByClass('ilMetaDataConfigGUI'));
        $this->addGeneral();
        // Options/Data for the field are only available after knowing the type of the field
        if ($this->field->getId()) {
            $this->addInputfield();
            $this->addFieldTypeOptions();
            $this->addFieldData();
            $this->addFormatters();
        }
        $this->addCommandButton('saveField', 'Save');
        $this->addCommandButton('cancelField', 'Cancel');
    }

    protected function addGeneral()
    {
        $header = new \ilFormSectionHeaderGUI();
        $header->setTitle('General');
        $this->addItem($header);

        $item = new \ilHiddenInputGUI('field_id');
        $item->setValue($this->field->getId());
        $this->addItem($item);

        $item = new \ilTextInputGUI('Identifier', 'identifier');
        $item->setRequired(true);
        $item->setInfo('A unique Identifier to retrieve this field by a name - besides the ID');
        $item->setValue($this->field->getIdentifier());
        $this->addItem($item);

        $types = array(
            'SRAG\\ILIAS\\Plugins\\MetaData\\Field\\TextField' => 'Text',
            'SRAG\\ILIAS\\Plugins\\MetaData\\Field\\TextareaField' => 'Textarea (Textarea, RichText)',
            'SRAG\\ILIAS\\Plugins\\MetaData\\Field\\DropdownField' => 'Dropdown (Select)',
            'SRAG\\ILIAS\\Plugins\\MetaData\\Field\\MultiDropdownField' => 'Dropdown with multiple selections (Checkboxes, Select2, AsmSelect)',
            'SRAG\\ILIAS\\Plugins\\MetaData\\Field\\IntegerField' => 'Integer',
            'SRAG\\ILIAS\\Plugins\\MetaData\\Field\\FloatField' => 'Float',
            'SRAG\\ILIAS\\Plugins\\MetaData\\Field\\DateTimeField' => 'DateTime',
            'SRAG\\ILIAS\\Plugins\\MetaData\\Field\\BooleanField' => 'Boolean',
            'SRAG\\ILIAS\\Plugins\\MetaData\\Field\\LocationField' => 'Location',
            UserField::class => "User",
            OrgUnitField::class => "Org unit",
            OrgUnitsField::class => "Org units"
        );
        $item = new \ilSelectInputGUI('Field Type', 'type');
        $item->setRequired(true);
        $item->setOptions($types);
        $item->setValue($this->field->getClass());
        $item->setDisabled($this->field->getId());
        $this->addItem($item);

        foreach ($this->language->getAvailableLanguages() as $lang) {
            $item = new \ilTextInputGUI("Label $lang", "label_$lang");
            $item->setRequired($this->language->getDefaultLanguage() == $lang);
            $item->setValue($this->field->getLabel($lang, false));
            $this->addItem($item);
            $item = new \ilTextAreaInputGUI("Description $lang", "description_$lang");
            $item->setValue($this->field->getDescription($lang, false));
            $this->addItem($item);
        }
    }

    protected function addFormatters()
    {
        $section = new \ilFormSectionHeaderGUI();
        $section->setTitle('Output Formatters');
        $this->addItem($section);
        $item = new ilAsmSelectInputGUI('Formatters', 'formatters');
        $item->setInfo('Stack formatters to influence the value returned by Record::getFormattedValue()');
        $classes = array(
            'SRAG\\ILIAS\\Plugins\\MetaData\\Formatter\\StringStorageValueFormatter',
            'SRAG\\ILIAS\\Plugins\\MetaData\\Formatter\\DropdownValueFormatter',
            'SRAG\\ILIAS\\Plugins\\MetaData\\Formatter\\MultiDropdownValueFormatter',
            'SRAG\\ILIAS\\Plugins\\MetaData\\Formatter\\Nl2brFormatter',
            'SRAG\\ILIAS\\Plugins\\MetaData\\Formatter\\HtmlEntitiesFormatter',
            'SRAG\\ILIAS\\Plugins\\MetaData\\Formatter\\UnorderedListFormatter',
            'SRAG\\ILIAS\\Plugins\\MetaData\\Formatter\\OrderedListFormatter',
            'SRAG\\ILIAS\\Plugins\\MetaData\\Formatter\\GoogleMapsFormatter',
            ObjectTitleFormatter::class
        );
        $options = array();
        foreach ($classes as $class) {
            $formatter = new $class();
            $label = $formatter->getTitle() . ' [' . $formatter->getInType() . ' -> ' . $formatter->getOutType() . ']';
            $options[$class] = $label;
        }
        $item->setOptions($options);
        $item->setValue($this->field->getFormatters());
        $this->addItem($item);
    }

    protected function addInputfield()
    {
        $header = new \ilFormSectionHeaderGUI();
        $header->setTitle('Inputfield');
        $this->addItem($header);
        $item = new \ilSelectInputGUI('Inputfield', 'inputfield');
        $item->setInfo("The Inputfield controls the rendering of this field in an ILIAS form");
        $item->setRequired(true);
        $options = array();
        $class = $this->field->getClass();
        /** @var Field $instance */
        $instance = new $class();
        foreach ($instance->getCompatibleInputfields() as $inputfield) {
            $options[$inputfield] = str_replace('SRAG\\ILIAS\\Plugins\\MetaData\\Inputfield\\', '', $inputfield);
        }
        $item->setOptions($options);
        $item->setValue($this->field->getInputfieldClass());
        $this->addItem($item);
    }

    protected function addFieldTypeOptions()
    {
        $data = $this->field->options()->getData();
        if (!count($data)) {
            return;
        }
        $header = new \ilFormSectionHeaderGUI();
        $header->setTitle('Field Type Options');
        $this->addItem($header);

        // Use some reflection magic to build our option fields
        $class = $this->field->getClass() . 'Options';
        $class = class_exists($class) ? $class : 'SRAG\\ILIAS\\Plugins\\Metadata\\Field\\FieldOptions';
        $reflection = new \ReflectionClass($class);
        foreach (array_reverse($reflection->getDefaultProperties()) as $property => $value) {
            if ($property == 'data') continue;
            $comment = $reflection->getProperty($property)->getDocComment();
            $label = (preg_match("/@label\s(.*)\n/", $comment, $matches)) ? $matches[1] : '';
            $info = (preg_match("/@description\s(.*)\n/", $comment, $matches)) ? $matches[1] : '';
            $class = (preg_match("/@formProperty\s(.*)\n/", $comment, $matches)) ? $matches[1] : '';
            /** @var \ilFormPropertyGUI $item */
            $item = new $class($label, 'option_' . $property);
            $item->setInfo($info);
            $this->addItem($item);
            if ($item instanceof \ilCheckboxInputGUI) {
                $item->setChecked((bool)$data[$property]);
            } else {
                $item->setValue($data[$property]);
            }
        }
    }

    protected function addFieldData()
    {
        if (!$this->field->supportsData()) {
            return;
        }
        $header = new \ilFormSectionHeaderGUI();
        $header->setTitle('Field Data');
        $this->addItem($header);

        foreach ($this->field->getData() as $field_data) {
            $item = new \ilTextInputGUI($field_data->getId(), 'field_data_' . $field_data->getId());
            $item->setValue(json_encode($field_data->getValues()));
            $this->addItem($item);
            $delete_item = new ilCustomInputGUI();
            $delete_item->setHtml(self::output()->getHTML(self::dic()->ui()->factory()->glyph()->remove()->withAdditionalOnLoadCode(function (string $id) use($field_data) : string {
                self::dic()->ctrl()->setParameterByClass(ilMetaDataConfigGUI::class, "field_data_id", $field_data->getId());
                $delete_link = self::dic()->ctrl()->getLinkTargetByClass(ilMetaDataConfigGUI::class, "deleteFieldData", "", true, false);
                self::dic()->ctrl()->clearParameterByClass(ilMetaDataConfigGUI::class, "field_data_id");

                Waiter::init(Waiter::TYPE_WAITER);

                return '
            $("#' . $id . '").click(function() {
                il.waiter.show();
            $.ajax({
                url: ' . json_encode($delete_link) . ',
                type: "GET"
            }).always(function() {
                il.waiter.hide();
            }).success(function () {
                $("#' . $id . '").parent().parent().prev().remove();
                $("#' . $id . '").parent().parent().remove();
            });
        });';
            })));
            $this->addItem($delete_item);
        }
        $item = new \ilTextAreaInputGUI('Add Data', 'add_data');
        $item->setRows(10);
        $item->setInfo('Add one value per line. To set the value for multiple languages, use a JSON string: {"de" : "Value DE", "fr" : "Value FR"}');
        $this->addItem($item);
    }

}