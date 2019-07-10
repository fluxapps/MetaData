<?php
namespace SRAG\ILIAS\Plugins\MetaData\Record;

use SRAG\ILIAS\Plugins\MetaData\Field\Field;
use SRAG\ILIAS\Plugins\MetaData\Formatter\Formatter;

/**
 * Class Record
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 */
class Record extends \ActiveRecord
{
	const TABLE_NAME = 'srmd_record';

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
    protected $group_id;

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
     * @var string
     *
     * @db_has_field    true
     * @db_fieldtype    text
     * @db_length       16
     * @db_index        true
     */
    protected $obj_type;

    /**
     * @var int
     *
     * @db_has_field    true
     * @db_fieldtype    integer
     * @db_length       8
     * @db_index        true
     */
    protected $obj_id;

    /**
     * @var string
     *
     * @db_has_field    true
     * @db_fieldtype    timestamp
     */
    protected $updated_at;

    /**
     * @var string
     *
     * @db_has_field    true
     * @db_fieldtype    timestamp
     */
    protected $created_at;

    /**
     * @var int
     *
     * @db_has_field    true
     * @db_fieldtype    integer
     * @db_length       8
     */
    protected $updated_user_id;

    /**
     * @var int
     *
     * @db_has_field    true
     * @db_fieldtype    integer
     * @db_length       8
     */
    protected $created_user_id;

    /**
     * @var mixed
     */
    protected $value;


    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        if ($this->value === null && $this->getField()) {
            $this->value = $this->getField()->getStorage()->getValue($this);
        }

        return $this->value;
    }


	public function getFormattedValue()
    {
	    if ($this->getField()) {
            $value = $this->getValue();
	        foreach ($this->getField()->getFormatters() as $class) {
	    	    /** @var Formatter $formatter */
	            $formatter = new $class();
                $value = $formatter->format($this, $value);
            }
            return $value;
	    }

	    return $this->getValue();
    }


    /**
     * @return Field
     */
    public function getField()
    {
        return Field::find($this->getFieldId());
    }

    public function create()
    {
        global $ilUser;

	    if(is_object($ilUser)) {
	       $this->created_user_id = $ilUser->getId();
		}

        $this->created_at = date('Y-m-d H:i:s');
        parent::create();
        $this->getField()->getStorage()->saveValue($this, $this->getValue());
    }

    public function update()
    {
        global $ilUser;

        $this->updated_user_id = $ilUser->getId();
        $this->updated_at = date('Y-m-d H:i:s');
        parent::update();
        $this->getField()->getStorage()->saveValue($this, $this->getValue());
    }

    public function delete()
    {
        parent::delete();
        $this->getField()->getStorage()->deleteValue($this);
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
    public function getFieldGroupId()
    {
        return $this->group_id;
    }

    /**
     * @inheritdoc
     */
    public function setFieldGroupId($group_id)
    {
        $this->group_id = $group_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getFieldId()
    {
        return $this->field_id;
    }

    /**
     * @inheritdoc
     */
    public function setFieldId($field_id)
    {
        $this->field_id = $field_id;
        return $this;
    }

    /**
     * @return string
     */
    public function getObjType()
    {
        return $this->obj_type;
    }

    /**
     * @inheritdoc
     */
    public function setObjType($obj_type)
    {
        $this->obj_type = $obj_type;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getObjId()
    {
        return $this->obj_id;
    }

    /**
     * @inheritdoc
     */
    public function setObjId($obj_id)
    {
        $this->obj_id = $obj_id;
        return $this;
    }

    /**
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @return int
     */
    public function getUpdatedUserId()
    {
        return $this->updated_user_id;
    }

    /**
     * @return int
     */
    public function getCreatedUserId()
    {
        return $this->created_user_id;
    }


    /**
     * @return string
     * @description Return the Name of your Database Table
     */
    public static function returnDbTableName()
    {
        return self::TABLE_NAME;
    }
}