<?php
namespace SRAG\ILIAS\Plugins\MetaData\Inputfield;

use SRAG\ILIAS\Plugins\MetaData\Field\Field;
use SRAG\ILIAS\Plugins\MetaData\Field\IntegerField;
use SRAG\ILIAS\Plugins\MetaData\Field\IntegerFieldOptions;
use SRAG\ILIAS\Plugins\MetaData\Record\Record;

/**
 * Class InputfieldInteger
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
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
        $input = new \ilNumberInputGUI($this->field->getLabel($this->lang), $this->getPostVar($record));
        if ($this->field->getDescription($this->lang)) {
            $input->setInfo($this->field->getDescription($this->lang));
        }
        if ($options->getMinValue()) {
            $input->setMinValue($options->getMinValue());
        }
        if ($options->getMaxValue()) {
            $input->setMaxValue($options->getMaxValue());
        }
        $input->setRequired($options->isRequired());
        $input->setValue($record->getValue());

        return array($input);
    }

    public function getRecordValue(Record $record, \ilPropertyFormGUI $form)
    {
        return $form->getInput($this->getPostVar($record));
    }

}