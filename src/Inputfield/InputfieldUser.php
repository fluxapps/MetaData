<?php

namespace SRAG\ILIAS\Plugins\MetaData\Inputfield;

use ilNonEditableValueGUI;
use ilTextInputGUI;
use ilUIPluginRouterGUI;
use SRAG\ILIAS\Plugins\MetaData\Field\UserField;
use SRAG\ILIAS\Plugins\MetaData\Record\Record;
use srmdGUI;

/**
 * Class InputfieldUser
 *
 * @package SRAG\ILIAS\Plugins\MetaData\Inputfield
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class InputfieldUser extends InputfieldInteger
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
            $input = new ilTextInputGUI($this->field->getLabel($this->lang), $this->getPostVar($record));
            $input->setRequired($this->field->options()->isRequired());
            $input->setValue($record->getValue());
            self::dic()->ctrl()->setParameterByClass(srmdGUI::class, "field_id", $this->field->getId());
            $input->setDataSource(self::dic()->ctrl()->getLinkTargetByClass([
                ilUIPluginRouterGUI::class,
                srmdGUI::class
            ], srmdGUI::CMD_USER_AUTOCOMPLETE, "", true, false));
            self::dic()->ctrl()->clearParameterByClass(srmdGUI::class, "field_id");
        }

        if ($this->field->getDescription($this->lang)) {
            $input->setInfo($this->field->getDescription($this->lang));
        }

        return [$input];
    }
}
