<?php

namespace SRAG\ILIAS\Plugins\MetaData\Field;

use SRAG\ILIAS\Plugins\MetaData\Inputfield\InputfieldOrgUnits;
use SRAG\ILIAS\Plugins\MetaData\Storage\OrgUnitsStorage;
use SRAG\ILIAS\Plugins\MetaData\Storage\Storage;

/**
 * Class OrgUnitsField
 *
 * @package SRAG\ILIAS\Plugins\MetaData\Field
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class OrgUnitsField extends Field
{

    /**
     * @inheritDoc
     *
     * @return OrgUnitsFieldOptions
     */
    public function options() : FieldOptions
    {
        return parent::options();
    }


    /**
     * @inheritDoc
     *
     * @return OrgUnitsStorage
     */
    public function getStorage() : Storage
    {
        return new OrgUnitsStorage();
    }


    /**
     * @inheritDoc
     */
    public function getCompatibleInputfields() : array
    {
        return [InputfieldOrgUnits::class];
    }


    /**
     * @inheritDoc
     *
     * @return OrgUnitsFieldOptions
     */
    protected function getFieldOptions(array $data) : FieldOptions
    {
        return new OrgUnitsFieldOptions($data);
    }
}
