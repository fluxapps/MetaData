<?php
namespace SRAG\ILIAS\Plugins\MetaData\Record;

use SRAG\ILIAS\Plugins\MetaData\Field\Field;
use SRAG\ILIAS\Plugins\MetaData\Field\FieldGroup;
use SRAG\ILIAS\Plugins\MetaData\Object\ConsumerObject;

/**
 * Class RecordQuery
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\MetaData\Record
 */
class RecordQuery
{

    /**
     * @var ConsumerObject
     */
    protected $object;

    /**
     * @var array
     */
    protected static $cache = array();

    /**
     * @param ConsumerObject $object
     */
    public function __construct(ConsumerObject $object)
    {
        $this->object = $object;
    }


    /**
     * Flush record cache
     *
     * @return $this
     */
    public function flushCache()
    {
        self::$cache = array();

        return $this;
    }


    /**
     * Return all records for the fields of the given field group,
     * ordered in the same order as the fields in the group
     *
     * @param FieldGroup $group
     * @return Record[]
     */
    public function getRecords(FieldGroup $group)
    {
        $key = $this->getCacheKey($group);
        if (isset(self::$cache[$key])) {
            return self::$cache[$key];
        }
        $records = Record::where(array(
            'obj_type' => $this->object->getType(),
            'obj_id' => $this->object->getId(),
            'group_id' => $group->getId()
        ))->get();
        // Sort records according to the fields in the field group
        $sorted = array();
        foreach ($group->getFieldIds() as $field_id) {
            /** @var Record $record */
            foreach ($records as $record) {
                $key2 = $this->getCacheKey($group, $record->getField());
                if (isset(self::$cache[$key2])) {
                    $record = self::$cache[$key2];
                } else {
                    self::$cache[$key2] = $record;
                }
                /** @var $record Record */
                if ($record->getFieldId() == $field_id) {
                    $sorted[] = $record;
                    break;
                }
            }
        }
        self::$cache[$key] = $sorted;

        return $sorted;
    }

    /**
     * @param FieldGroup $group
     * @param Field $field
     * @return Record|null
     */
    public function getRecord(FieldGroup $group, Field $field)
    {
        $key = $this->getCacheKey($group, $field);
        if (isset(self::$cache[$key])) {
            return self::$cache[$key];
        }
        /** @var Record $record */
        $record = Record::where(array(
            'obj_type' => $this->object->getType(),
            'obj_id' => $this->object->getId(),
            'group_id' => $group->getId(),
            'field_id' => $field->getId(),
        ))->first();
        self::$cache[$key] = $record;

        return $record;
    }

    /**
     * Generate a cache key for records
     *
     * @param FieldGroup $group
     * @param Field|null $field
     * @return string
     */
    protected function getCacheKey(FieldGroup $group, Field $field = null)
    {
        $keys = array(
            $this->object->getType(),
            $this->object->getId(),
            $group->getId(),
        );
        if ($field) {
            $keys[] = $field->getId();
        }
        return implode('_', $keys);
    }

}