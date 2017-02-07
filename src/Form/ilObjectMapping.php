<?php
namespace SRAG\ILIAS\Plugins\MetaData\Form;

use arConnector;
use SRAG\ILIAS\Plugins\MetaData\Field\FieldGroup;
use SRAG\ILIAS\Plugins\MetaData\Language\ilLanguage;
use SRAG\ILIAS\Plugins\MetaData\Language\Language;

/**
 * Class ilObjectMapping
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 */
class ilObjectMapping extends \ActiveRecord
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
     * @var string
     * @db_has_field    true
     * @db_fieldtype    text
     * @db_length       8
     */
    protected $obj_type;


    /**
     * @var string
     * @db_has_field    true
     * @db_fieldtype    text
     * @db_length       1204
     */
    protected $tab_title = array();

    /**
     * @var array
     * @db_has_field    true
     * @db_fieldtype    text
     * @db_length       1024
     */
    protected $field_group_ids = array();

    /**
     * @var int
     * @db_has_field    true
     * @db_fieldtype    integer
     * @db_length       8
     */
    protected $active = 1;

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


    public function sleep($field_name)
    {
        switch ($field_name) {
            case 'field_group_ids':
            case 'tab_title':
                return json_encode($this->{$field_name});
        }

        return parent::sleep($field_name);
    }


    public function wakeUp($field_name, $field_value)
    {
        switch ($field_name) {
            case 'field_group_ids':
            case 'tab_title':
                return json_decode($field_value, true);
        }

        return parent::wakeUp($field_name, $field_value);
    }


    public function getTabTitleArray()
    {
        return $this->tab_title;
    }


    /**
     * @return string
     */
    public function getTabTitle($lang = '')
    {
        if ($lang && isset($this->tab_title[$lang])) {
            return $this->tab_title[$lang];
        }

        // Try to return in default language if available, otherwise empty string
        $default = $this->language->getDefaultLanguage();

        return (isset($this->tab_title[$default])) ? $this->tab_title[$default] : '';

    }


    public function setTabTitleArray(array $titles)
    {
        $this->tab_title = $titles;
    }


    /**
     * @param string $tab_title
     * @param $lang
     */
    public function setTabTitle($tab_title, $lang = '')
    {
        $this->tab_title[$lang] = $tab_title;
    }


    /**
     * @return array
     */
    public function getFieldGroupIds()
    {
        return $this->field_group_ids;
    }


    /**
     * @param array $field_group_ids
     */
    public function setFieldGroupIds($field_group_ids)
    {
        $this->field_group_ids = $field_group_ids;
    }


    /**
     * @return int
     */
    public function getActive()
    {
        return $this->active;
    }


    /**
     * @param int $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }


    /**
     * @return string
     */
    public function getObjType()
    {
        return $this->obj_type;
    }


    /**
     * @param string $obj_type
     */
    public function setObjType($obj_type)
    {
        $this->obj_type = $obj_type;
    }


    /**
     * @return FieldGroup[]
     */
    public function getFieldGroups()
    {
        $groups = array();
        foreach ($this->field_group_ids as $id) {
            $groups[] = FieldGroup::find($id);
        }

        return $groups;
    }

    /**
     * @return string
     */
    static function returnDbTableName()
    {
        return 'srmd_object_mapping';
    }
}