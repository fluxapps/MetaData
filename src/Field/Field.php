<?php

namespace SRAG\ILIAS\Plugins\MetaData\Field;

use SRAG\ILIAS\Plugins\MetaData\Inputfield\Inputfield;
use SRAG\ILIAS\Plugins\MetaData\Language\ilLanguage;
use SRAG\ILIAS\Plugins\MetaData\Language\Language;
use SRAG\ILIAS\Plugins\MetaData\Record\Record;
use SRAG\ILIAS\Plugins\MetaData\Storage\Storage;

/**
 * Class Field
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\MetaData\Field
 */
abstract class Field extends \ActiveRecord
{

    const TABLE_NAME = 'srmd_field';
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
     * Fully qualified classname of the concrete field class
     *
     * @var string
     *
     * @db_has_field    true
     * @db_fieldtype    text
     * @db_length       255
     * @db_is_notnull   true
     */
    protected $class;
    /**
     * @var FieldOptions
     *
     * @db_has_field    true
     * @db_fieldtype    text
     * @db_length       4000
     */
    protected $options;
    /**
     * @var array
     *
     * @db_has_field    true
     * @db_fieldtype    text
     * @db_length       1204
     */
    protected $label = array();
    /**
     * @var array
     *
     * @db_has_field    true
     * @db_fieldtype    text
     * @db_length       2048
     */
    protected $description = array();
    /**
     * @var string
     *
     * @db_has_field    true
     * @db_fieldtype    text
     * @db_length       255
     */
    protected $inputfield_class;
    /**
     * @var array
     *
     * @db_has_field    true
     * @db_fieldtype    text
     * @db_length       1204
     */
    protected $formatters = array();
    /**
     * @var Language
     */
    protected $language;
    /**
     * @var Storage
     */
    protected $storage;
    /**
     * @var FieldOptions
     */
    protected $field_options;


    /**
     * @param int               $primary_key
     * @param \arConnector|NULL $connector
     */
    public function __construct($primary_key = 0, \arConnector $connector = null)
    {
        parent::__construct($primary_key, $connector);
        $this->language = new ilLanguage();
        $this->field_options = $this->getFieldOptions((array) $this->options);
    }


    /**
     * Return the FieldOptions class
     *
     * @param array $data Data for the field from database
     *
     * @return FieldOptions
     */
    abstract protected function getFieldOptions(array $data);


    /**
     * Find a field by identifier
     *
     * @param string $identifier
     *
     * @return null|Field
     */
    public static function findByIdentifier($identifier)
    {
        $field = NullField::where(array('identifier' => $identifier))->first();
        if (!$field) {
            return null;
        }

        return self::find($field->getId());
    }


    /**
     * Wraps a factory to create instances of concrete fields (subclasses of this class)
     *
     * @param       $primary_key
     * @param array $add_constructor_args
     *
     * @return Field
     */
    public static function find($primary_key, array $add_constructor_args = array())
    {
        if (get_called_class() == 'SRAG\ILIAS\Plugins\MetaData\Field\Field') {
            // We must return an object of the subclass
            $field = NullField::find($primary_key, $add_constructor_args);
            if ($field) {
                $class = $field->getClass();

                return $class::find($primary_key, $add_constructor_args);
            }
        } else {
            // A subclass is calling -> go directly to parent because of recursion
            return parent::find($primary_key, $add_constructor_args);
        }

        return null;
    }


    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }


    /**
     * @param string $class
     */
    public function setClass($class)
    {
        $this->class = $class;
    }


    /**
     * Tries to find the object and throws an Exception if object is not found, instead of returning null
     *
     * @param       $primary_key
     * @param array $add_constructor_args
     *
     * @return Field
     * @throws \arException
     */
    public static function findOrFail($primary_key, array $add_constructor_args = array())
    {
        $obj = static::find($primary_key, $add_constructor_args);
        if (is_null($obj)) {
            throw new \arException(\arException::RECORD_NOT_FOUND);
        }

        return $obj;
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
            case 'label':
            case 'description':
            case 'formatters':
                return json_encode($this->{$field_name});
            case 'options':
                return ($this->field_options !== null) ? json_encode($this->field_options->getData()) : array();
        }

        return parent::sleep($field_name);
    }


    public function wakeUp($field_name, $field_value)
    {
        switch ($field_name) {
            case 'label':
            case 'description':
            case 'formatters':
            case 'options':
                return json_decode($field_value, true);
        }

        return parent::wakeUp($field_name, $field_value);
    }


    /**
     * @return bool
     */
    public function supportsData()
    {
        return false;
    }


    /**
     * Get the storage object which is responsible to store incoming data for this field
     *
     * @return Storage
     */
    abstract public function getStorage();


    /**
     * @return FieldOptions
     */
    public function options()
    {
        return $this->field_options;
    }


    public function create()
    {
        if (!$this->inputfield_class) {
            $inputfields = $this->getCompatibleInputfields();
            $this->inputfield_class = $inputfields[0];
        }
        if (!$this->class) {
            $this->class = get_class($this);
        }
        parent::create();
    }


    /**
     * Return an array of class names of available inputfields for this field
     *
     * @return array
     */
    abstract public function getCompatibleInputfields();


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
    public function getLabel($lang = '', $substitute_default = true)
    {
        if ($lang && isset($this->label[$lang])) {
            return $this->label[$lang];
        }
        if (!$substitute_default) {
            return '';
        }
        // Try to return in default language if available, otherwise empty string
        $default = $this->language->getDefaultLanguage();

        return (isset($this->label[$default])) ? $this->label[$default] : '';
    }


    /**
     * @param string $label
     * @param string $lang
     */
    public function setLabel($label, $lang)
    {
        $this->label[$lang] = $label;
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
        $default = $this->language->getDefaultLanguage();

        return (isset($this->description[$default])) ? $this->description[$default] : '';
    }


    /**
     * @param string $description
     * @param string $lang
     */
    public function setDescription($description, $lang)
    {
        $this->description[$lang] = $description;
    }


    /**
     * @return FieldData[]
     */
    public function getData()
    {
        static $cache = array();
        if (isset($cache[$this->getId()])) {
            return $cache[$this->getId()];
        }
        $data = ArFieldData::where(array(
            'field_id' => $this->getId()
        ))->orderBy('sort')->get();
        $cache[$this->getId()] = $data;

        return $data;
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
    public function getInputfieldClass()
    {
        return $this->inputfield_class;
    }


    /**
     * @param string $inputfield_class
     */
    public function setInputfieldClass($inputfield_class)
    {
        $this->inputfield_class = $inputfield_class;
    }


    /**
     * @return array
     */
    public function getFormatters()
    {
        return $this->formatters;
    }


    /**
     * @param array $formatters
     */
    public function setFormatters($formatters)
    {
        $this->formatters = $formatters;
    }


    /**
     * Append a formatter at the end of the formatter chain
     *
     * @param string $formatter Fully qualified class name of formatter
     */
    public function appendFormatter($formatter)
    {
        $this->formatters[] = $formatter;
    }
}