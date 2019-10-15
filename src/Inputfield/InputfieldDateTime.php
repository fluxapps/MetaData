<?php
namespace SRAG\ILIAS\Plugins\MetaData\Inputfield;

use ilDate;
use ilDatePresentation;
use ilNonEditableValueGUI;
use SRAG\ILIAS\Plugins\MetaData\Field\DateTimeField;
use SRAG\ILIAS\Plugins\MetaData\Field\DateTimeFieldOptions;
use SRAG\ILIAS\Plugins\MetaData\FormProperty\ilDateTimeInput2GUI;
use SRAG\ILIAS\Plugins\MetaData\Record\Record;

/**
 * Class InputfieldDateTime
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\MetaData\Inputfield
 */
class InputfieldDateTime extends BaseInputfield
{

    /**
     * @param DateTimeField $field
     * @param string $lang
     */
    public function __construct(DateTimeField $field, $lang = '')
    {
        parent::__construct($field, $lang);
    }


    public function getILIASFormInputs(Record $record)
    {
        $options = $this->field->options();
        if ($options->isOnlyDisplay()) {
            $input = new ilNonEditableValueGUI($this->field->getLabel($this->lang));
            $input->setValue(ilDatePresentation::formatDate(new ilDate($record->getValue(), IL_CAL_DATETIME)));
        } else {
        $input = new ilDateTimeInput2GUI($this->field->getLabel($this->lang), $this->getPostVar($record));
        $input->setLocale($this->lang);
        if ($record->getValue()) {
            $input->setValue(new \DateTime($record->getValue()));
        }
        $input->setRequired($options->isRequired());
        if ($options->isShowTime()) {
            $input->setOption('enableTime', true);
        }
        if ($options->getDateFormat()) {
            $input->setOption('altFormat', $options->getDateFormat());
        }
        }
        if ($this->field->getDescription($this->lang)) {
            $input->setInfo($this->field->getDescription($this->lang));
        }

        return array($input);
    }

    public function getRecordValue(Record $record, \ilPropertyFormGUI $form)
    {
        $datestring = $form->getInput($this->getPostVar($record));
        return new \DateTime($datestring);
    }

}