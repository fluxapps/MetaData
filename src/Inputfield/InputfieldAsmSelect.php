<?php
namespace SRAG\ILIAS\Plugins\MetaData\Inputfield;

use SRAG\ILIAS\Plugins\MetaData\Field\Field;
use SRAG\ILIAS\Plugins\MetaData\Field\MultiDropdownField;
use SRAG\ILIAS\Plugins\MetaData\FormProperty\ilAsmSelectInputGUI;
use SRAG\ILIAS\Plugins\MetaData\Record\Record;

/**
 * Class InputfieldAsmSelect
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\MetaData\Inputfield
 */
class InputfieldAsmSelect extends BaseInputfield
{

    public function __construct(MultiDropdownField $field, $lang = '')
    {
        parent::__construct($field, $lang);
    }


    public function getILIASFormInputs(Record $record)
    {
        $options = $this->field->options();
        $input = new ilAsmSelectInputGUI($this->field->getLabel($this->lang), $this->getPostVar($record));
        if ($this->field->getDescription($this->lang)) {
            $input->setInfo($this->field->getDescription($this->lang));
        }
        $input->setRequired($options->isRequired());
        $data = array();
        foreach ($this->field->getData() as $field_data) {
            $data[$field_data->getId()] = $field_data->getValue($this->lang);
        }
        $input->setOptions($data);
        $input->setValue($record->getValue());

        return array($input);
    }

    public function getRecordValue(Record $record, \ilPropertyFormGUI $form)
    {
        return $form->getInput($this->getPostVar($record));
    }

}