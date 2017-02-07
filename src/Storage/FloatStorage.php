<?php
namespace SRAG\ILIAS\Plugins\MetaData\Storage;

use SRAG\ILIAS\Plugins\MetaData\RecordValue\FloatRecordValue;
use SRAG\ILIAS\Plugins\MetaData\RecordValue\IntegerRecordValue;
use SRAG\ILIAS\Plugins\MetaData\Record\Record;

/**
 * Class FloatStorage
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\MetaData\Storage
 */
class FloatStorage extends AbstractStorage
{

    protected function validateValue($value)
    {
        if ($value !== null && !is_numeric($value)) {
            throw new \InvalidArgumentException("'$value' is not numeric");
        }
    }

    /**
     * @inheritdoc
     */
    protected function normalizeValue($value)
    {
        return (float) $value;
    }

    /**
     * @inheritdoc
     */
    protected function getRecordValue(Record $record)
    {
        $record_value = FloatRecordValue::where(array('record_id' => $record->getId()))->first();
        if (!$record_value) {
            $record_value = new FloatRecordValue();
            $record_value->setRecordId($record->getId());
        }
        return $record_value;
    }
}