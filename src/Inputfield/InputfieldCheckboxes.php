<?php

namespace SRAG\ILIAS\Plugins\MetaData\Inputfield;

use ilNonEditableValueGUI;
use SRAG\ILIAS\Plugins\MetaData\Field\Field;
use SRAG\ILIAS\Plugins\MetaData\Field\MultiDropdownField;
use SRAG\ILIAS\Plugins\MetaData\Field\TextField;
use SRAG\ILIAS\Plugins\MetaData\Language\ilLanguage;
use SRAG\ILIAS\Plugins\MetaData\Language\Language;
use SRAG\ILIAS\Plugins\MetaData\Record\Record;

require_once('./Services/Form/classes/class.ilMultiSelectInputGUI.php');

/**
 * Class InputfieldCheckboxes
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\MetaData\Inputfield
 */
class InputfieldCheckboxes extends BaseInputfield
{

    public function __construct(MultiDropdownField $field, $lang = '')
    {
        parent::__construct($field, $lang);
    }


    public function getILIASFormInputs(Record $record)
    {
        $options = $this->field->options();
        $data = array();
        foreach ($this->field->getData() as $field_data) {
            $data[$field_data->getId()] = $field_data->getValue($this->lang);
        }
        if ($options->isOnlyDisplay()) {
            $input = new ilNonEditableValueGUI($this->field->getLabel($this->lang));
            $input->setValue(nl2br(implode("\n", array_map(function ($value) use ($data) {
                return $data[$value];
            }, $record->getValue(), $this->field->getData())), false));
        } else {
            $input = new \ilMultiSelectInputGUI($this->field->getLabel($this->lang), $this->getPostVar($record));
            $input->setRequired($options->isRequired());
            $input->setOptions($data);
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