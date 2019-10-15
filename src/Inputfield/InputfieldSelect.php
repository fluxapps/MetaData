<?php
namespace SRAG\ILIAS\Plugins\MetaData\Inputfield;

use ilNonEditableValueGUI;
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
        $options = array();
        foreach ($this->field->getData() as $field_data) {
            $options[$field_data->getId()] = $field_data->getValue($this->lang);
        }
        if ($field_options->isPrependEmptyOption()) {
            $options = array('' => '') + $options;
        }
        if ($field_options->isOnlyDisplay()) {
            $input = new ilNonEditableValueGUI($this->field->getLabel($this->lang));
            $input->setValue($options[$record->getValue()]);
        } else {
        $input = new \ilSelectInputGUI($this->field->getLabel($this->lang), $this->getPostVar($record));
        $input->setRequired($field_options->isRequired());
        $input->setOptions($options);
        $input->setValue($record->getValue());
        }
        if ($this->field->getDescription($this->lang)) {
            $input->setInfo($this->field->getDescription($this->lang));
        }

        return array($input);
    }

    public function getRecordValue(Record $record, \ilPropertyFormGUI $form)
    {
        return $form->getInput($this->getPostVar($record));
    }

}