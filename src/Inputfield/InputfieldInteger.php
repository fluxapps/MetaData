<?php

namespace SRAG\ILIAS\Plugins\MetaData\Inputfield;

use ilNonEditableValueGUI;
use SRAG\ILIAS\Plugins\MetaData\Field\Field;
use SRAG\ILIAS\Plugins\MetaData\Field\IntegerField;
use SRAG\ILIAS\Plugins\MetaData\Field\IntegerFieldOptions;
use SRAG\ILIAS\Plugins\MetaData\Record\Record;

/**
 * Class InputfieldInteger
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\MetaData\Inputfield
 */
class InputfieldInteger extends BaseInputfield
{

    public function __construct(IntegerField $field, $lang = '')
    {
        parent::__construct($field, $lang);
    }


    public function getILIASFormInputs(Record $record)
    {
        $options = $this->field->options();
        if ($options->isOnlyDisplay()) {
            $input = new ilNonEditableValueGUI($this->field->getLabel($this->lang));
        } else {
            $input = new \ilNumberInputGUI($this->field->getLabel($this->lang), $this->getPostVar($record));
            if ($options->getMinValue()) {
                $input->setMinValue($options->getMinValue());
            }
            if ($options->getMaxValue()) {
                $input->setMaxValue($options->getMaxValue());
            }
            $input->setRequired($options->isRequired());
        }
        if ($this->field->getDescription($this->lang)) {
            $input->setInfo($this->field->getDescription($this->lang));
        }
        $input->setValue($record->getValue());

        return array($input);
    }


    public function getRecordValue(Record $record, \ilPropertyFormGUI $form)
    {
        return $form->getInput($this->getPostVar($record));
    }
}