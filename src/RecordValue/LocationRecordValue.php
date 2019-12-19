<?php

namespace SRAG\ILIAS\Plugins\MetaData\RecordValue;

/**
 * Class LocationRecordValue
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\MetaData\RecordValue
 */
class LocationRecordValue extends \ActiveRecord implements RecordValue
{

    const TABLE_NAME = 'srmd_location';
    /**
     * @var int
     *
     * @db_has_field    true
     * @db_fieldtype    integer
     * @db_length       8
     * @db_is_primary   true
     * @db_sequence     true
     */
    protected $id = 0;
    /**
     * @var int
     *
     * @db_has_field    true
     * @db_fieldtype    integer
     * @db_length       8
     */
    protected $record_id;
    /**
     * @var float
     *
     * @db_has_field    true
     * @db_fieldtype    float
     */
    protected $latitude;
    /**
     * @var float
     *
     * @db_has_field    true
     * @db_fieldtype    float
     */
    protected $longitude;
    /**
     * @var int
     *
     * @db_has_field    true
     * @db_fieldtype    integer
     * @db_length       8
     */
    protected $zoom;
    /**
     * @var string
     *
     * @db_has_field    true
     * @db_fieldtype    text
     * @db_length       512
     */
    protected $address;


    /**
     * @return string
     * @description Return the Name of your Database Table
     * @deprecated
     */
    static function returnDbTableName()
    {
        return self::TABLE_NAME;
    }


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @return int
     */
    public function getRecordId()
    {
        return $this->record_id;
    }


    /**
     * @param int $record_id
     */
    public function setRecordId($record_id)
    {
        $this->record_id = $record_id;
    }


    /**
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }


    /**
     * @param float $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }


    /**
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }


    /**
     * @param float $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }


    /**
     * @return int
     */
    public function getZoom()
    {
        return $this->zoom;
    }


    /**
     * @param int $zoom
     */
    public function setZoom($zoom)
    {
        $this->zoom = $zoom;
    }


    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }


    /**
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }
}