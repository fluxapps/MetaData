<?php
namespace SRAG\ILIAS\Plugins\MetaData\Storage;
use SRAG\ILIAS\Plugins\MetaData\Language\Language;
use SRAG\ILIAS\Plugins\MetaData\Record\Record;

/**
 * Class AbstractStorage
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\MetaData\Storage
 */
abstract class AbstractStorage implements Storage
{

    /**
     * Validate the given value, throw an InvalidArgumentException if it is not valid
     *
     * @param $value
     */
    abstract protected function validateValue($value);

    /**
     * Normalize the value, e.g. sanitizing and converting to the required type
     *
     * @param $value
     * @return mixed
     */
    abstract protected function normalizeValue($value);

    /**
     * @param Record $record
     * @return \ActiveRecord
     */
    abstract protected function getRecordValue(Record $record);

    /**
     * @inheritdoc
     */
    public function getValue(Record $record)
    {
        $record_value = $this->getRecordValue($record);
        return $record_value->getValue();
    }

    /**
     * @inheritdoc
     */
    public function saveValue(Record $record, $value)
    {
        $this->validateValue($value);
        $record_value = $this->getRecordValue($record);
        $record_value->setValue($this->normalizeValue($value));
        $record_value->save();
    }

    /**
     * @inheritdoc
     */
    public function deleteValue(Record $record)
    {
        $record_value = $this->getRecordValue($record);
        $record_value->delete();
    }


}