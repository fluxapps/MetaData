<?php
namespace SRAG\ILIAS\Plugins\MetaData\Storage;
use SRAG\ILIAS\Plugins\MetaData\Record\Record;
use SRAG\ILIAS\Plugins\MetaData\RecordValue\DateTimeRecordValue;

/**
 * Class DateTimeStorage
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 */
class DateTimeStorage extends AbstractStorage
{

    /**
     * @inheritdoc
     */
    protected function validateValue($value)
    {
        if (!is_string($value) && !$value instanceof \DateTime) {
            throw new \InvalidArgumentException("Provided value is not a datestring or instance of DateTime: " . $value);
        }
    }

    /**
     * @inheritdoc
     */
    protected function normalizeValue($value)
    {
        return ($value instanceof \DateTime) ? $value->format('Y-m-d H:i:s') : $value;
    }

    /**
     * @inheritdoc
     */
    protected function getRecordValue(Record $record)
    {
        $record_value = DateTimeRecordValue::where(array('record_id' => $record->getId()))->first();
        if (!$record_value) {
            $record_value = new DateTimeRecordValue();
            $record_value->setRecordId($record->getId());
        }
        return $record_value;
    }

}