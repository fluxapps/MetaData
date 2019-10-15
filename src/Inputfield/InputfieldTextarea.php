<?php
namespace SRAG\ILIAS\Plugins\MetaData\Inputfield;

use ilNonEditableValueGUI;
use SRAG\ILIAS\Plugins\MetaData\Field\TextareaField;
use SRAG\ILIAS\Plugins\MetaData\Record\Record;

/**
 * Class InputfieldTextarea
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\MetaData\Inputfield
 */
class InputfieldTextarea extends InputfieldText
{

    public function __construct(TextareaField $field, $lang = '')
    {
        parent::__construct($field, $lang);
    }


    public function getILIASFormInputs(Record $record)
    {
        $inputs = array();
        $value = $record->getValue();
        $options = $this->field->options();
        // Check if this text field should be rendered for all languages or just the default
        foreach ($this->getLanguages() as $lang) {
            if ($options->isOnlyDisplay()) {
                $input = new ilNonEditableValueGUI($this->getLabel($lang));
                $input->setValue($record->getValue());
            } else {
            $input = new \ilTextAreaInputGUI($this->getLabel($lang), $this->getPostVar($record) . "_$lang");
            $rows = $options->getNRows() ? $options->getNRows() : 8;
            $input->setRows($rows);
            // Field is required only in the default language, even if rendered for multiple languages
            $input->setRequired($options->isRequired() && $lang == $this->language->getDefaultLanguage());
            }
            if ($this->field->getDescription($this->lang)) {
                $input->setInfo($this->field->getDescription($this->lang));
            }
            if (isset($value[$lang])) {
                $input->setValue($value[$lang]);
            }
            $inputs[] = $input;
        }
        $this->initLanguageTabs($this->getPostVar($record));
        return $inputs;
    }

}