<?php
namespace SRAG\ILIAS\Plugins\MetaData\Field;

use SRAG\ILIAS\Plugins\MetaData\Storage\IntegerStorage;

/**
 * Class DropdownField
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\MetaData\Field
 */
class DropdownField extends Field
{

    /**
     * @inheritdoc
     */
    protected function getFieldOptions(array $data)
    {
        return new DropdownFieldOptions($data);
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
        return array('SRAG\\ILIAS\\Plugins\\MetaData\\Inputfield\\InputfieldSelect');
    }


    public function supportsData()
    {
        return true;
    }


    /**
     * @return DropdownFieldOptions
     */
    public function options()
    {
        return parent::options();
    }


    public function create()
    {
        // Set the default formatter
        if (!count($this->formatters)) {
            $this->formatters[] = 'SRAG\\ILIAS\\Plugins\\MetaData\\Formatter\\DropdownValueFormatter';
        }
        parent::create();
    }
}