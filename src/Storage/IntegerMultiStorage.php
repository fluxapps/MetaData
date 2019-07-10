<?php
namespace SRAG\ILIAS\Plugins\MetaData\Storage;

use SRAG\ILIAS\Plugins\MetaData\RecordValue\IntegerMultiRecordValue;
use SRAG\ILIAS\Plugins\MetaData\Record\Record;

/**
 * Class IntegerMultiStorage
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\MetaData\Storage
 */
class IntegerMultiStorage extends AbstractStorage
{

    protected function validateValue($value)
    {
        if (!is_array($value)) {
            throw new \InvalidArgumentException("'$value' must be passed as array");
        }
        foreach ($value as $sort => $int) {
            if (!is_numeric($int)) {
                throw new \InvalidArgumentException("'$int' is not numeric");
            }
        }
    }

    /**
     * @inheritdoc
     */
    protected function normalizeValue($value)
    {
        $normalized = array();
        foreach ($value as $int) {
            $normalized[] = (int)$int;
        }
        return $normalized;
    }

    /**
     * @inheritdoc
     */
    protected function getRecordValue(Record $record)
    {
        return IntegerMultiRecordValue::where(array('record_id' => $record->getId()))->orderBy('sort')->get();
    }

    /**
     * @inheritdoc
     */
    public function getValue(Record $record)
    {
        $return = array();
        /** @var IntegerMultiRecordValue $record_value */
        foreach ($this->getRecordValue($record) as $record_value) {
            $return[] = $record_value->getValue();
        }
        return $return;
    }

    /**
     * @inheritdoc
     */
    public function saveValue(Record $record, $value)
    {
        $this->validateValue($value);
        $record_values = array_values((array)$this->getRecordValue($record)); // Re-index zero based
        // Note: $sort is zero based!
        foreach ($this->normalizeValue($value) as $sort => $int) {
            if (isset($record_values[$sort])) {
                // Reuse an existing object and remove it from the $record_values array
                $record_value = $record_values[$sort];
                unset($record_values[$sort]);
            } else {
                // No existing object available, create a new one
                $record_value = new IntegerMultiRecordValue();
                $record_value->setRecordId($record->getId());
            }
            $record_value->setSort($sort);
            $record_value->setValue($int);
            $record_value->save();
        }
        // If we still have objects left in $record_values, these need to be deleted now!
        foreach ($record_values as $record_value) {
            $record_value->delete();
        }
    }
}