<?php
namespace SRAG\ILIAS\Plugins\MetaData\Field;

use SRAG\ILIAS\Plugins\MetaData\Storage\TextStorage;

/**
 * Class TextareaField
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\MetaData\Field
 */
class TextareaField extends TextField
{

    protected function getFieldOptions(array $data)
    {
        return new TextareaFieldOptions($data);
    }


    /**
     * @return TextareaFieldOptions
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
        return new TextStorage($this->language);
    }


    public function getCompatibleInputfields()
    {
        return array(
            'SRAG\\ILIAS\\Plugins\\MetaData\\Inputfield\\InputfieldTextarea',
            'SRAG\\ILIAS\\Plugins\\MetaData\\Inputfield\\InputfieldRichtext',
        );
    }
}