<?php

namespace SRAG\ILIAS\Plugins\MetaData\Field;

use SRAG\ILIAS\Plugins\MetaData\Storage\IntegerStorage;

/**
 * Class IntegerField
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\MetaData\Field
 */
class IntegerField extends Field
{

    /**
     * @return IntegerFieldOptions
     */
    public function options()
    {
        return parent::options();
    }


    /**
     * @inheritdoc
     */
    public function getStorage()
    {
        return new IntegerStorage();
    }


    public function getCompatibleInputfields()
    {
        return array('SRAG\\ILIAS\\Plugins\\MetaData\\Inputfield\\InputfieldInteger');
    }


    /**
     * @inheritdoc
     */
    protected function getFieldOptions(array $data)
    {
        return new IntegerFieldOptions($data);
    }
}