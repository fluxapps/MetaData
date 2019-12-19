<?php

namespace SRAG\ILIAS\Plugins\MetaData\Storage;

use SRAG\ILIAS\Plugins\MetaData\RecordValue\IntegerRecordValue;
use SRAG\ILIAS\Plugins\MetaData\Record\Record;
use SRAG\ILIAS\Plugins\MetaData\RecordValue\LocationRecordValue;

/**
 * Class LocationStorage
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\MetaData\Storage
 */
class LocationStorage extends AbstractStorage
{

    /**
     * @inheritdoc
     */
    public function getValue(Record $record)
    {
        $record_value = $this->getRecordValue($record);

        return array(
            'lat'     => $record_value->getLatitude(),
            'long'    => $record_value->getLongitude(),
            'zoom'    => $record_value->getZoom(),
            'address' => $record_value->getAddress(),
        );
    }


    /**
     * @inheritdoc
     */
    protected function getRecordValue(Record $record)
    {
        $record_value = LocationRecordValue::where(array('record_id' => $record->getId()))->first();
        if (!$record_value) {
            $record_value = new LocationRecordValue();
            $record_value->setRecordId($record->getId());
        }

        return $record_value;
    }


    /**
     * @inheritdoc
     */
    public function saveValue(Record $record, $value)
    {
        $this->validateValue($value);
        $value = $this->normalizeValue($value);
        $record_value = $this->getRecordValue($record);
        $record_value->setLatitude($value['lat']);
        $record_value->setLongitude($value['long']);
        $record_value->setZoom($value['zoom']);
        $record_value->setAddress($value['address']);
        $record_value->save();
    }


    protected function validateValue($value)
    {
        if (!is_array($value)) {
            throw new \InvalidArgumentException("'$value' is not an array");
        }
        foreach (array('lat', 'long', 'zoom') as $property) {
            if (!array_key_exists($property, $value)) {
                throw new \InvalidArgumentException("'$property' is missing in array, array must have lat/long/zoom keys");
            }
        }
    }


    /**
     * @inheritdoc
     */
    protected function normalizeValue($value)
    {
        if (!isset($value['address'])) {
            $value['address'] = '';
        }

        return $value;
    }
}