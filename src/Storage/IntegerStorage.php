<?php
namespace SRAG\ILIAS\Plugins\MetaData\Storage;

use SRAG\ILIAS\Plugins\MetaData\RecordValue\IntegerRecordValue;
use SRAG\ILIAS\Plugins\MetaData\Record\Record;

/**
 * Class IntegerStorage
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\MetaData\Storage
 */
class IntegerStorage extends AbstractStorage
{

    protected function validateValue($value)
    {
        if ($value && !is_numeric($value)) {
            throw new \InvalidArgumentException("'$value' is not numeric");
        }
    }

    /**
     * @inheritdoc
     */
    protected function normalizeValue($value)
    {
        return ($value == '') ? null : $value;
    }

    /**
     * @inheritdoc
     */
    protected function getRecordValue(Record $record)
    {
        $record_value = IntegerRecordValue::where(array('record_id' => $record->getId()))->first();
        if (!$record_value) {
            $record_value = new IntegerRecordValue();
            $record_value->setRecordId($record->getId());
        }
        return $record_value;
    }
}