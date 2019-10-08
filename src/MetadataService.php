<?php

namespace SRAG\ILIAS\Plugins\MetaData;

use ilObject;
use SRAG\ILIAS\Plugins\MetaData\Field\Field;
use SRAG\ILIAS\Plugins\MetaData\Field\FieldGroup;
use SRAG\ILIAS\Plugins\MetaData\Object\ilConsumerObject;
use SRAG\ILIAS\Plugins\MetaData\Record\Record;
use SRAG\ILIAS\Plugins\MetaData\Record\RecordQuery;
use SRAG\ILIAS\Plugins\MetaData\Util\SingletonTrait;

/**
 * Class MetadataService
 *
 * @author Theodor Truffer <tt@studer-raimann.ch>
 */
class MetadataService
{
    use SingletonTrait;

    /**
     * @param ilObject $object
     * @param string   $field_group_id
     * @param string   $field_id
     * @param          $value
     */
    public function setValue(ilObject $object, string $field_group_id, string $field_id, $value)
    {
        $record = $this->getRecord($object, $field_group_id, $field_id);
        $record->setValue($value);
        $record->save();
    }


    /**
     * @param ilObject $object
     * @param string   $field_group_id
     * @param string   $field_id
     *
     * @return mixed|null
     */
    public function getValue(ilObject $object, string $field_group_id, string $field_id)
    {
        $record = $this->getRecord($object, $field_group_id, $field_id);
        return $record ? $record->getValue() : null;
    }

    /**
     * @param ilObject $object
     * @param string   $field_group_id
     * @param string   $field_id
     *
     * @return mixed|null
     */
    public function getFormattedValue(ilObject $object, string $field_group_id, string $field_id)
    {
        $record = $this->getRecord($object, $field_group_id, $field_id);
        return $record ? $record->getFormattedValue() : null;
    }


    /**
     * @param ilObject $object
     * @param string   $field_group_id
     * @param string   $field_id
     *
     * @return Record|null
     */
    public function getRecord(ilObject $object, string $field_group_id, string $field_id)
    {
        $consumer = new ilConsumerObject($object);
        $query = new RecordQuery($consumer);
        $fieldGroup = FieldGroup::findByIdentifier($field_group_id);
        $field = Field::findByIdentifier($field_id);
        $record = $query->getRecord($fieldGroup, $field);
        if (!$record) {
            $record = new Record();
            $record->setFieldGroupId($fieldGroup->getId());
            $record->setFieldId($field->getId());
            $record->setObjType($object->getType());
            $record->setObjId($object->getId());
        }
        return $record;
    }
}