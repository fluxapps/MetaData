<?php
namespace SRAG\ILIAS\Plugins\MetaData\Storage;
use SRAG\ILIAS\Plugins\MetaData\Record\Record;
use SRAG\ILIAS\Plugins\MetaData\RecordValue\StringRecordValue;
use SRAG\ILIAS\Plugins\MetaData\RecordValue\TextRecordValue;

/**
 * Class TextStorage
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 */
class TextStorage extends StringStorage
{

    /**
     * @param Record $record
     * @return StringRecordValue[]
     */
    protected function getRecordValue(Record $record)
    {
        return TextRecordValue::where(array('record_id' => $record->getId()))->get();
    }


    /**
     * @inheritdoc
     */
    public function saveValue(Record $record, $value)
    {
        $this->validateValue($value);
        foreach ($this->normalizeValue($value) as $lang => $string) {
            $record_value = TextRecordValue::where(array(
                'record_id' => $record->getId(),
                'lang' => $lang,
            ))->first();
            if (!$record_value) {
                $record_value = new TextRecordValue();
                $record_value->setRecordId($record->getId());
                $record_value->setLang($lang);
            }
            $record_value->setValue($string);
            $record_value->save();
        }
    }
}