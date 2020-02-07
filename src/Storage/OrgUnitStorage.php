<?php

namespace SRAG\ILIAS\Plugins\MetaData\Storage;

use ilObjOrgUnit;
use InvalidArgumentException;

/**
 * Class OrgUnitStorage
 *
 * @package SRAG\ILIAS\Plugins\MetaData\Storage
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class OrgUnitStorage extends IntegerStorage
{

    /**
     * @inheritDoc
     */
    protected function validateValue($value)
    {
        $value = current($value);

        parent::validateValue($value);

        if (!empty($value) && !ilObjOrgUnit::_exists($value, true)) {
            throw new InvalidArgumentException("'$value' is not an exists org unit id!");
        }
    }


    /**
     * @inheritDoc
     */
    protected function normalizeValue($value)
    {
        return parent::normalizeValue(current($value));
    }
}
