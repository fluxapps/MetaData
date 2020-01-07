<?php

namespace SRAG\ILIAS\Plugins\MetaData\RecordValue;

/**
 * Class StringRecordValue
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\MetaData\RecordValue
 */
class StringRecordValue extends \ActiveRecord implements RecordValue
{

    const TABLE_NAME = 'srmd_string';
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
     * @db_index        true
     */
    protected $record_id;
    /**
     * @var string
     *
     * @db_has_field    true
     * @db_fieldtype    text
     * @db_length       2
     */
    protected $lang;
    /**
     * @var string
     *
     * @db_has_field    true
     * @db_fieldtype    text
     * @db_length       2048
     */
    protected $value;


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
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }


    /**
     * @param int $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }


    /**
     * @return string
     */
    public function getLang()
    {
        return $this->lang;
    }


    /**
     * @param string $lang
     */
    public function setLang($lang)
    {
        $this->lang = $lang;
    }
}