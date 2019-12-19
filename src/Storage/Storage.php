<?php

namespace SRAG\ILIAS\Plugins\MetaData\Storage;

use SRAG\ILIAS\Plugins\MetaData\Record\Record;

/**
 * Interface Storage
 *
 * @package SRAG\ILIAS\Plugins\MetaData\Storage
 */
interface Storage
{

    /**
     * Get the value of the provided Record object, returns null if no value exists
     *
     * @param Record $record
     *
     * @return mixed
     */
    public function getValue(Record $record);


    /**
     * Create or update the value to the given Record object.
     * Throws an InvalidArgumentException if the value is not passed in its correct form.
     *
     * @param Record $record
     * @param        $value
     *
     * @throws \InvalidArgumentException
     */
    public function saveValue(Record $record, $value);


    /**
     * Delete the value of the given Record
     *
     * @param Record $record
     *
     * @return mixed
     */
    public function deleteValue(Record $record);
}