<?php

namespace SRAG\ILIAS\Plugins\MetaData\Inputfield;

use ilNonEditableValueGUI;
use ilPropertyFormGUI;
use srag\CustomInputGUIs\MetaData\MultiSelectSearchNewInputGUI\MultiSelectSearchNewInputGUI;
use srag\CustomInputGUIs\MetaData\MultiSelectSearchNewInputGUI\ObjectChildrenAjaxAutoCompleteCtrl;
use SRAG\ILIAS\Plugins\MetaData\Field\OrgUnitsField;
use SRAG\ILIAS\Plugins\MetaData\Record\Record;

/**
 * Class InputfieldOrgUnits
 *
 * @package SRAG\ILIAS\Plugins\MetaData\Inputfield
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class InputfieldOrgUnits extends BaseInputfield
{

    /**
     * @inheritDoc
     */
    public function __construct(OrgUnitsField $field, string $lang = "")
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
            $input->setValue(nl2br(implode("\n",
                array_map(function (int $org_unit_ref_id) : string {
                    return self::dic()->objDataCache()->lookupTitle(self::dic()->objDataCache()->lookupObjId($org_unit_ref_id));
                }, $record->getValue())),
                false));
        } else {
            $input = new MultiSelectSearchNewInputGUI($this->field->getLabel($this->lang), $this->getPostVar($record));
            $input->setRequired($this->field->options()->isRequired());
            $input->setValue($record->getValue());
            //$cmdClass self::dic()->ctrl()->getCmdClass(); // is broken with namespace (ilCtrl), use with filter_input the original raw value
            self::dic()->ctrl()->setParameterByClass(ObjectChildrenAjaxAutoCompleteCtrl::class, "field_id", $this->field->getId());
            $input->setAjaxAutoCompleteCtrl(new ObjectChildrenAjaxAutoCompleteCtrl("orgu", $this->field->options()->getOrgUnitParentRefId()));
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
