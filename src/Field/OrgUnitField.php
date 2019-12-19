<?php

namespace SRAG\ILIAS\Plugins\MetaData\Field;

use SRAG\ILIAS\Plugins\MetaData\Inputfield\InputfieldOrgUnit;
use SRAG\ILIAS\Plugins\MetaData\Storage\Storage;
use SRAG\ILIAS\Plugins\MetaData\Storage\OrgUnitStorage;

/**
 * Class OrgUnitField
 *
 * @package SRAG\ILIAS\Plugins\MetaData\Field
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class OrgUnitField extends Field
{

    /**
     * @inheritDoc
     *
     * @return OrgUnitFieldOptions
     */
    protected function getFieldOptions(array $data) : FieldOptions
    {
        return new OrgUnitFieldOptions($data);
    }


    /**
     * @inheritDoc
     *
     * @return OrgUnitFieldOptions
     */
    public function options() : FieldOptions
    {
        return parent::options();
    }


    /**
     * @inheritDoc
     *
     * @return OrgUnitStorage
     */
    public function getStorage() : Storage
    {
        return new OrgUnitStorage();
    }


    /**
     * @inheritDoc
     */
    public function getCompatibleInputfields() : array
    {
        return [InputfieldOrgUnit::class];
    }
}
