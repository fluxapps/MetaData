<?php

namespace SRAG\ILIAS\Plugins\MetaData\Inputfield;

use ilNonEditableValueGUI;
use ilOrgUnitPathStorage;
use ilSelectInputGUI;
use SRAG\ILIAS\Plugins\MetaData\Field\OrgUnitField;
use SRAG\ILIAS\Plugins\MetaData\Record\Record;

/**
 * Class InputfieldOrgUnit
 *
 * @package SRAG\ILIAS\Plugins\MetaData\Inputfield
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class InputfieldOrgUnit extends InputfieldInteger
{

    /**
     * @inheritDoc
     */
    public function __construct(OrgUnitField $field, string $lang = "")
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
            $input = new ilSelectInputGUI($this->field->getLabel($this->lang), $this->getPostVar($record));
            $input->setRequired($this->field->options()->isRequired());
            $input->setOptions([0 => ""] + ilOrgUnitPathStorage::orderBy("path")->getArray("ref_id", "path"));
            $input->setValue($record->getValue());
        }

        if ($this->field->getDescription($this->lang)) {
            $input->setInfo($this->field->getDescription($this->lang));
        }

        return [$input];
    }
}
