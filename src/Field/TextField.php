<?php

namespace SRAG\ILIAS\Plugins\MetaData\Field;

use SRAG\ILIAS\Plugins\MetaData\Storage\StringStorage;

/**
 * Class TextField
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\MetaData\Field
 */
class TextField extends Field
{

    /**
     * @inheritdoc
     */
    public function getStorage()
    {
        return new StringStorage($this->language);
    }


    public function getCompatibleInputfields()
    {
        return array('SRAG\\ILIAS\\Plugins\\MetaData\\Inputfield\\InputfieldText');
    }


    /**
     * @return TextFieldOptions
     */
    public function options()
    {
        return parent::options();
    }


    public function create()
    {
        // Set the default formatter
        if (!count($this->formatters)) {
            $this->formatters[] = 'SRAG\\ILIAS\\Plugins\\MetaData\\Formatter\\StringStorageValueFormatter';
        }
        parent::create();
    }


    /**
     * @inheritdoc
     */
    protected function getFieldOptions(array $data)
    {
        return new TextFieldOptions($data);
    }
}