<?php

namespace SRAG\ILIAS\Plugins\MetaData\Storage;

use ilObjOrgUnit;
use InvalidArgumentException;

/**
 * Class OrgUnitsStorage
 *
 * @package SRAG\ILIAS\Plugins\MetaData\Storage
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class OrgUnitsStorage extends IntegerMultiStorage
{

    /**
     * @inheritDoc
     */
    protected function validateValue($value)
    {
        parent::validateValue($value);

        foreach ($value as $int) {
            if (!empty($int) && !ilObjOrgUnit::_exists($int, true)) {
                throw new InvalidArgumentException("'$int' is not an exists org unit id!");
            }
        }
    }
}
