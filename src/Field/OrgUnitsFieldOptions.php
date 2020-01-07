<?php

namespace SRAG\ILIAS\Plugins\MetaData\Field;

use ilObjOrgUnit;

/**
 * Class OrgUnitsFieldOptions
 *
 * @package SRAG\ILIAS\Plugins\MetaData\Field
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class OrgUnitsFieldOptions extends FieldOptions
{

    /**
     * @label        Org unit parent ref id
     * @formProperty ilNumberInputGUI
     * @description  Root if empty
     * @var int
     */
    protected $org_unit_parent_ref_id = 0;


    /**
     * @inheritDoc
     */
    public function __construct(array $data = [])
    {
        parent::__construct($data);

        $this->data = array_merge($this->data, [
            "org_unit_parent_ref_id" => 0
        ], $data);
    }


    /**
     * @return int
     */
    public function getOrgUnitParentRefId() : int
    {
        return intval($this->data["org_unit_parent_ref_id"] ?: ilObjOrgUnit::getRootOrgRefId());
    }


    /**
     * @param int $org_unit_parent_ref_id
     */
    public function setOrgUnitParentRefId(int $org_unit_parent_ref_id)
    {
        $this->data["org_unit_parent_ref_id"] = $org_unit_parent_ref_id;
    }
}
