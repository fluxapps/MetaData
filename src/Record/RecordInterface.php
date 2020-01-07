<?php

namespace SRAG\ILIAS\Plugins\MetaData\Record;

/**
 * Record
 *
 * @package SRAG\ILIAS\Plugins\MetaData\Record
 */
interface RecordInterface
{

    /**
     * Get the unique ID of this record
     *
     * @return $this
     */
    public function getId();


    /**
     * Get the ID of the FieldGroup this record belongs to
     *
     * @return int
     */
    public function getFieldGroupId();


    /**
     * Set the ID of the FieldGroup this record belongs to
     *
     * @param int $id
     *
     * @return $this
     */
    public function setFieldGroupId($id);


    /**
     * Get the ID of the Field this record belongs to
     *
     * @return int
     */
    public function getFieldId();


    /**
     * Set the ID of the Field this record belongs to
     *
     * @param int $id
     *
     * @return $this
     */
    public function setFieldId($id);


    /**
     * @return string
     */
    public function getObjType();


    /**
     * @param string $type
     *
     * @return $this
     */
    public function setObjType($type);


    /**
     * @return int
     */
    public function getObjId();


    /**
     * @param $id
     *
     * @return $this
     */
    public function setObjId($id);


    /**
     * Get the timestamp when this record was updated
     *
     * @return string
     */
    public function getUpdatedAt();


    /**
     * Get the timestamp when this record was created
     *
     * @return string
     */
    public function getCreatedAt();


    /**
     * Get the ILIAS user-ID that updated the record
     *
     * @return string
     */
    public function getUpdatedUserId();


    /**
     * Get the ILIAS user-ID that created the record
     *
     * @return string
     */
    public function getCreatedUserId();


    /**
     * Get the value of this record
     *
     * @return mixed
     */
    public function getValue();


    /**
     * Set the value of this record
     *
     * @param mixed $value
     *
     * @return $this
     */
    public function setValue($value);


    /**
     * Return a formatted representation of the value
     *
     * @return mixed
     */
    public function getFormattedValue();
}