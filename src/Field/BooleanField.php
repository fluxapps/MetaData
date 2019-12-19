<?php

namespace SRAG\ILIAS\Plugins\MetaData\Field;

use SRAG\ILIAS\Plugins\MetaData\Storage\IntegerStorage;

/**
 * Class BooleanField
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\MetaData\Field
 */
class BooleanField extends Field
{

    /**
     * @inheritdoc
     */
    public function getStorage()
    {
        return new IntegerStorage();
    }


    public function getCompatibleInputfields()
    {
        return array('SRAG\\ILIAS\\Plugins\\MetaData\\Inputfield\\InputfieldCheckbox');
    }


    /**
     * @inheritdoc
     */
    protected function getFieldOptions(array $data)
    {
        return new FieldOptions($data);
    }
}