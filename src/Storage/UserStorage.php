<?php

namespace SRAG\ILIAS\Plugins\MetaData\Storage;

use ilObjUser;
use InvalidArgumentException;

/**
 * Class UserStorage
 *
 * @package SRAG\ILIAS\Plugins\MetaData\Storage
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class UserStorage extends IntegerStorage
{

    /**
     * @inheritDoc
     */
    protected function validateValue($value)
    {
        parent::validateValue($value);

        if (empty($value) || !ilObjUser::_exists($value)) {
            throw new InvalidArgumentException("'$value' is not an exists user id!");
        }
    }
}
