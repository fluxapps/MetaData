<?php
namespace SRAG\ILIAS\Plugins\MetaData\Storage;
use SRAG\ILIAS\Plugins\MetaData\Language\Language;
use SRAG\ILIAS\Plugins\MetaData\Record\Record;
use SRAG\ILIAS\Plugins\MetaData\RecordValue\StringRecordValue;

/**
 * Class StringStorage
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 */
class StringStorage extends AbstractStorage
{

    /**
     * @var Language
     */
    protected $lang;

    public function __construct(Language $lang)
    {
        $this->lang = $lang;
    }


    /**
     * @param array $array
     * @return bool
     */
    protected function hasStringKeys(array $array) {
        return count(array_filter(array_keys($array), 'is_string')) > 0;
    }


    /**
     * @inheritdoc
     */
    protected function validateValue($value)
    {
        if (is_array($value) && !$this->hasStringKeys($value)) {
            throw new \InvalidArgumentException("The keys of the given array must represent the language code");
        } else if (!is_array($value) && !is_string($value)) {
            throw new \InvalidArgumentException("Value must be either an array (key=language, value=string) or a string in the default language. Given: " . $value);
        }
    }


    /**
     * @inheritdoc
     */
    protected function normalizeValue($value)
    {
        return (is_string($value)) ? array($this->lang->getDefaultLanguage() => $value) : $value;
    }

    /**
     * @param Record $record
     * @return StringRecordValue[]
     */
    protected function getRecordValue(Record $record)
    {
        return StringRecordValue::where(array('record_id' => $record->getId()))->get();
    }


    /**
     * @inheritdoc
     */
    public function getValue(Record $record)
    {
        $return = array();
        /** @var StringRecordValue $record_value */
        foreach ($this->getRecordValue($record) as $record_value) {
            $return[$record_value->getLang()] = $record_value->getValue();
        }

        return $return;
    }

    /**
     * @inheritdoc
     */
    public function saveValue(Record $record, $value)
    {
        $this->validateValue($value);
        foreach ($this->normalizeValue($value) as $lang => $string) {
            $record_value = StringRecordValue::where(array(
                'record_id' => $record->getId(),
                'lang' => $lang,
            ))->first();
            if (!$record_value) {
                $record_value = new StringRecordValue();
                $record_value->setRecordId($record->getId());
                $record_value->setLang($lang);
            }
            $record_value->setValue($string);
            $record_value->save();
        }
    }


    /**
     * @inheritdoc
     */
    public function deleteValue(Record $record)
    {
        foreach ($this->getRecordValue($record) as $record_value) {
            $record_value->delete();
        }
    }
}