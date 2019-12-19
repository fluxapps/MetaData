<?php

namespace SRAG\ILIAS\Plugins\MetaData\Field;

use SRAG\ILIAS\Plugins\MetaData\Storage\IntegerMultiStorage;
use SRAG\ILIAS\Plugins\MetaData\Storage\StringStorage;

/**
 * Class MultiDropdownField
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\MetaData\Field
 */
class MultiDropdownField extends Field
{

    /**
     * @inheritdoc
     */
    public function getStorage()
    {
        return new IntegerMultiStorage();
    }


    public function getCompatibleInputfields()
    {
        return array(
            'SRAG\\ILIAS\\Plugins\\MetaData\\Inputfield\\InputfieldCheckboxes',
            'SRAG\\ILIAS\\Plugins\\MetaData\\Inputfield\\InputfieldAsmSelect',
        );
    }


    public function create()
    {
        // Set the default formatter
        if (!count($this->formatters)) {
            $this->formatters[] = 'SRAG\\ILIAS\\Plugins\\MetaData\\Formatter\\MultiDropdownValueFormatter';
        }
        parent::create();
    }


    public function supportsData()
    {
        return true;
    }


    /**
     * @inheritdoc
     */
    protected function getFieldOptions(array $data)
    {
        return new FieldOptions($data);
    }
}