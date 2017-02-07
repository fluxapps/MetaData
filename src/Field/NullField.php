<?php
namespace SRAG\ILIAS\Plugins\MetaData\Field;

use SRAG\ILIAS\Plugins\MetaData\Exception\Exception;
use SRAG\ILIAS\Plugins\MetaData\Language\Language;
use SRAG\ILIAS\Plugins\MetaData\Storage\IntegerStorage;
use SRAG\ILIAS\Plugins\MetaData\Storage\Storage;

/**
 * Class NullField
 *
 * A dummy field to create an empty instance of a field (as the base field is marked abstract).
 * Note that this field can't be persisted in the database!
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\MetaData\Field
 */
class NullField extends Field
{

    /**
     * @inheritdoc
     */
    protected function getFieldOptions(array $data)
    {
        return new FieldOptions($data);
    }


    /**
     * @inheritdoc
     */
    public function getStorage()
    {
        return null;
    }


    public function getCompatibleInputfields()
    {
        return array();
    }


    public function create()
    {
        throw new Exception("NullField can't be stored in DB");
    }


    public function update()
    {
        throw new Exception("NullField can't be stored in DB");
    }


    public function delete()
    {
        throw new Exception("NullField can't be stored in DB");
    }
}