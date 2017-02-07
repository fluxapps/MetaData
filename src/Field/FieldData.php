<?php
namespace SRAG\ILIAS\Plugins\MetaData\Field;
use arConnector;
use SRAG\ILIAS\Plugins\MetaData\Language\ilLanguage;
use SRAG\ILIAS\Plugins\MetaData\Language\Language;

/**
 * Class FieldData
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\MetaData\Field
 */
class FieldData extends \ActiveRecord
{

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
    protected $field_id;

    /**
     * @var array
     *
     * @db_has_field    true
     * @db_fieldtype    text
     * @db_length       512
     */
    protected $value = array();

    /**
     * @var int
     *
     * @db_has_field    true
     * @db_fieldtype    integer
     * @db_length       8
     */
    protected $sort;

    /**
     * @var Language
     */
    protected $language;

    public function __construct($primary_key = 0, arConnector $connector = NULL)
    {
        parent::__construct($primary_key, $connector);
        $this->language = new ilLanguage();
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
    public function getFieldId()
    {
        return $this->field_id;
    }

    /**
     * @param int $field_id
     */
    public function setFieldId($field_id)
    {
        $this->field_id = $field_id;
    }

    /**
     * @param array $values
     */
    public function setValues(array $values)
    {
        $this->value = $values;
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return $this->value;
    }

    /**
     * @param string $lang
     * @return string
     */
    public function getValue($lang = '')
    {
        if ($lang && isset($this->value[$lang])) {
            return $this->value[$lang];
        }

        // Try to return in default language if available, otherwise empty string
        $default = $this->language->getDefaultLanguage();
        return (isset($this->value[$default])) ? $this->value[$default] : '';
    }


    /**
     * @param array $value
     * @param $lang
     */
    public function setValue($value, $lang)
    {
        $this->value[$lang] = $value;
    }

    /**
     * @return int
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @param int $sort
     */
    public function setSort($sort)
    {
        $this->sort = $sort;
    }

    public function sleep($field_name)
    {
        switch ($field_name) {
            case 'value':
                return json_encode($this->{$field_name});
        }
        return parent::sleep($field_name);
    }

    public function wakeUp($field_name, $field_value)
    {
        switch ($field_name) {
            case 'value':
                return json_decode($field_value, true);
        }
        return parent::wakeUp($field_name, $field_value);
    }


    /**
     * @return string
     * @description Return the Name of your Database Table
     */
    static function returnDbTableName()
    {
        return 'srmd_field_data';
    }
}