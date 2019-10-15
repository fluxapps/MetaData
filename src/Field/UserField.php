<?php

namespace SRAG\ILIAS\Plugins\MetaData\Field;

use SRAG\ILIAS\Plugins\MetaData\Inputfield\InputfieldUser;
use SRAG\ILIAS\Plugins\MetaData\Storage\Storage;
use SRAG\ILIAS\Plugins\MetaData\Storage\UserStorage;

/**
 * Class UserField
 *
 * @package SRAG\ILIAS\Plugins\MetaData\Field
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class UserField extends IntegerField
{

    /**
     * @inheritDoc
     *
     * @return UserFieldOptions
     */
    protected function getFieldOptions(array $data) : FieldOptions
    {
        return new UserFieldOptions($data);
    }


    /**
     * @inheritDoc
     *
     * @return UserFieldOptions
     */
    public function options() : FieldOptions
    {
        return parent::options();
    }


    /**
     * @inheritDoc
     *
     * @return UserStorage
     */
    public function getStorage() : Storage
    {
        return new UserStorage();
    }


    /**
     * @inheritDoc
     */
    public function getCompatibleInputfields() : array
    {
        return [InputfieldUser::class];
    }
}
