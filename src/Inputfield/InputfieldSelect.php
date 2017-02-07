<?php
namespace SRAG\ILIAS\Plugins\MetaData\Inputfield;

use SRAG\ILIAS\Plugins\MetaData\Field\DropdownField;
use SRAG\ILIAS\Plugins\MetaData\Record\Record;

/**
 * Class InputfieldSelect
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\MetaData\Inputfield
 */
class InputfieldSelect extends BaseInputfield
{

    public function __construct(DropdownField $field, $lang = '')
    {
        parent::__construct($field, $lang);
    }


    public function getILIASFormInputs(Record $record)
    {
        $field_options = $this->field->options();
        $input = new \ilSelectInputGUI($this->field->getLabel($this->lang), $this->getPostVar($record));
        if ($this->field->getDescription($this->lang)) {
            $input->setInfo($this->field->getDescription($this->lang));
        }
        $input->setRequired($field_options->isRequired());
        $options = array();
        foreach ($this->field->getData() as $field_data) {
            $options[$field_data->getId()] = $field_data->getValue($this->lang);
        }
        if ($field_options->isPrependEmptyOption()) {
            $options = array('' => '') + $options;
        }
        $input->setOptions($options);
        $input->setValue($record->getValue());

        return array($input);
    }

    public function getRecordValue(Record $record, \ilPropertyFormGUI $form)
    {
        return $form->getInput($this->getPostVar($record));
    }

}