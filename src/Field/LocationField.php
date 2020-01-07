<?php

namespace SRAG\ILIAS\Plugins\MetaData\Field;

use SRAG\ILIAS\Plugins\MetaData\Storage\IntegerStorage;
use SRAG\ILIAS\Plugins\MetaData\Storage\LocationStorage;

/**
 * Class LocationField
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\MetaData\Field
 */
class LocationField extends Field
{

    /**
     * @inheritdoc
     */
    public function getStorage()
    {
        return new LocationStorage();
    }


    public function getCompatibleInputfields()
    {
        return array('SRAG\\ILIAS\\Plugins\\MetaData\\Inputfield\\InputfieldGoogleMaps');
    }


    public function create()
    {
        // Set the default formatter
        if (!count($this->formatters)) {
            $this->formatters[] = 'SRAG\\ILIAS\\Plugins\\MetaData\\Formatter\\GoogleMapsFormatter';
        }
        parent::create();
    }


    /**
     * @inheritdoc
     */
    protected function getFieldOptions(array $data)
    {
        return new FieldOptions($data);
    }
}