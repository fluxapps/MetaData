<?php

namespace SRAG\ILIAS\Plugins\MetaData\Field;

use arConnector;
use SRAG\ILIAS\Plugins\MetaData\Language\ilLanguage;
use SRAG\ILIAS\Plugins\MetaData\Language\Language;

/**
 * Class FieldGroup
 *
 * Groups some fields together with a title and description
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\MetaData\Field
 */
class FieldGroup extends \ActiveRecord
{

    const TABLE_NAME = 'srmd_field_group';
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
     * @var string
     *
     * @db_has_field    true
     * @db_fieldtype    text
     * @db_length       255
     * @db_is_unique    true
     * @db_is_notnull   true
     */
    protected $identifier;
    /**
     * @var array
     *
     * @db_has_field    true
     * @db_fieldtype    text
     * @db_length       1204
     */
    protected $title = array();
    /**
     * @var array
     *
     * @db_has_field    true
     * @db_fieldtype    text
     * @db_length       2048
     */
    protected $description = array();
    /**
     * @var array
     *
     * @db_has_field    true
     * @db_fieldtype    text
     * @db_length       1204
     */
    protected $field_ids = array();
    /**
     * @var Language
     */
    protected $lang;


    /**
     * @param int              $primary_key
     * @param arConnector|NULL $connector
     */
    public function __construct($primary_key = 0, arConnector $connector = null)
    {
        $this->lang = new ilLanguage();
        parent::__construct($primary_key, $connector);
    }


    /**
     * @param $identifier
     *
     * @return FieldGroup
     */
    public static function findByIdentifier($identifier)
    {
        return self::where(array('identifier' => $identifier))->first();
    }


    /**
     * @return string
     */
    static function returnDbTableName()
    {
        return self::TABLE_NAME;
    }


    public function sleep($field_name)
    {
        switch ($field_name) {
            case 'field_ids':
            case 'title':
            case 'description':
                return json_encode($this->{$field_name});
        }

        return parent::sleep($field_name);
    }


    public function wakeUp($field_name, $field_value)
    {
        switch ($field_name) {
            case 'field_ids':
            case 'title':
            case 'description':
                return json_decode($field_value, true);
        }

        return parent::wakeUp($field_name, $field_value);
    }


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }


    /**
     * @param string $identifier
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }


    /**
     * @param string $lang
     * @param bool   $substitute_default
     *
     * @return string
     */
    public function getTitle($lang = '', $substitute_default = true)
    {
        if ($lang && isset($this->title[$lang])) {
            return $this->title[$lang];
        }
        if (!$substitute_default) {
            return '';
        }
        // Try to return in default language if available, otherwise empty string
        $default = $this->lang->getDefaultLanguage();

        return (isset($this->title[$default])) ? $this->title[$default] : '';
    }


    /**
     * @param string $title
     * @param string $lang
     */
    public function setTitle($title, $lang)
    {
        $this->title[$lang] = $title;
    }


    /**
     * @param string $lang
     * @param bool   $substitute_default
     *
     * @return string
     */
    public function getDescription($lang = '', $substitute_default = true)
    {
        if ($lang && isset($this->description[$lang])) {
            return $this->description[$lang];
        }
        if (!$substitute_default) {
            return '';
        }
        // Try to return in default language if available, otherwise empty string
        $default = $this->lang->getDefaultLanguage();

        return (isset($this->description[$default])) ? $this->description[$default] : '';
    }


    /**
     * @param string $description
     * @param        $lang
     */
    public function setDescription($description, $lang)
    {
        $this->description[$lang] = $description;
    }


    /**
     * @return Field[]
     */
    public function getFields()
    {
        $fields = array();
        foreach ($this->getFieldIds() as $field_id) {
            $field = Field::find($field_id);
            if ($field) {
                $fields[] = $field;
            }
        }

        return $fields;
    }


    /**
     * @return array
     */
    public function getFieldIds()
    {
        return $this->field_ids;
    }


    /**
     * @param array $ids
     */
    public function setFieldIds(array $ids)
    {
        $this->field_ids = $ids;
    }
}