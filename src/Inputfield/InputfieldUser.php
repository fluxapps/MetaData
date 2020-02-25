<?php

namespace SRAG\ILIAS\Plugins\MetaData\Inputfield;

use ilNonEditableValueGUI;
use ilPropertyFormGUI;
use srag\CustomInputGUIs\MetaData\MultiSelectSearchNewInputGUI\MultiSelectSearchNewInputGUI;
use srag\CustomInputGUIs\MetaData\MultiSelectSearchNewInputGUI\UsersAjaxAutoCompleteCtrl;
use SRAG\ILIAS\Plugins\MetaData\Field\UserField;
use SRAG\ILIAS\Plugins\MetaData\Record\Record;

/**
 * Class InputfieldUser
 *
 * @package SRAG\ILIAS\Plugins\MetaData\Inputfield
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class InputfieldUser extends BaseInputfield
{

    /**
     * @inheritDoc
     */
    public function __construct(UserField $field, string $lang = "")
    {
        parent::__construct($field, $lang);
    }


    /**
     * @inheritDoc
     */
    public function getILIASFormInputs(Record $record) : array
    {
        if ($this->field->options()->isOnlyDisplay()) {
            $input = new ilNonEditableValueGUI($this->field->getLabel($this->lang));
            $input->setValue(self::dic()->objDataCache()->lookupTitle($record->getValue()));
        } else {
            $input = new MultiSelectSearchNewInputGUI($this->field->getLabel($this->lang), $this->getPostVar($record));
            $input->setRequired($this->field->options()->isRequired());
            if ($record->getValue()) {
                $input->setValue([$record->getValue()]);
            }
            //$cmdClass self::dic()->ctrl()->getCmdClass(); // is broken with namespace (ilCtrl), use with filter_input the original raw value
            self::dic()->ctrl()->setParameterByClass(UsersAjaxAutoCompleteCtrl::class, "field_id", $this->field->getId());
            $input->setAjaxAutoCompleteCtrl(new UsersAjaxAutoCompleteCtrl());
            $input->setLimitCount(1);
        }

        if ($this->field->getDescription($this->lang)) {
            $input->setInfo($this->field->getDescription($this->lang));
        }

        return [$input];
    }


    /**
     * @inheritDoc
     */
    public function getRecordValue(Record $record, ilPropertyFormGUI $form)
    {
        return $form->getItemByPostVar($this->getPostVar($record))->getValue();
    }
}
