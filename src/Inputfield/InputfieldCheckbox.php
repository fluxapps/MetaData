<?php
namespace SRAG\ILIAS\Plugins\MetaData\Inputfield;

use SRAG\ILIAS\Plugins\MetaData\Record\Record;

/**
 * Class InputfieldCheckbox
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\MetaData\Inputfield
 */
class InputfieldCheckbox extends BaseInputfield
{

    public function getILIASFormInputs(Record $record)
    {
        $input = new \ilCheckboxInputGUI($this->field->getLabel($this->lang), $this->getPostVar($record));
        if ($this->field->getDescription($this->lang)) {
            $input->setInfo($this->field->getDescription($this->lang));
        }
        $input->setChecked((bool) $record->getValue());

        return array($input);
    }

    public function getRecordValue(Record $record, \ilPropertyFormGUI $form)
    {
        return (int) $form->getInput($this->getPostVar($record));
    }

}