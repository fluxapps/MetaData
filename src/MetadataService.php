<?php

namespace SRAG\ILIAS\Plugins\MetaData;

use ilMetaDataPlugin;
use ilObject;
use srag\DIC\MetaData\DICTrait;
use SRAG\ILIAS\Plugins\MetaData\Field\Field;
use SRAG\ILIAS\Plugins\MetaData\Field\FieldGroup;
use SRAG\ILIAS\Plugins\MetaData\Field\NullField;
use SRAG\ILIAS\Plugins\MetaData\Form\ilObjectMapping;
use SRAG\ILIAS\Plugins\MetaData\Object\ConsumerObject;
use SRAG\ILIAS\Plugins\MetaData\Object\ilConsumerObject;
use SRAG\ILIAS\Plugins\MetaData\Record\Record;
use SRAG\ILIAS\Plugins\MetaData\Record\RecordQuery;
use SRAG\ILIAS\Plugins\MetaData\Util\SingletonTrait;

/**
 * Class MetadataService
 *
 * @author studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author Theodor Truffer <tt@studer-raimann.ch>
 */
class MetadataService
{

    use DICTrait;
    use SingletonTrait;
    const PLUGIN_CLASS_NAME = ilMetaDataPlugin::class;
    const SHOW_CONTEXT_EDIT_IN_TAB = 1;
    const SHOW_CONTEXT_SHOW_RIGHT_BLOCK = 2;
    const SHOW_CONTEXT_SHOW_INFO_SCREEN = 3;


    /**
     * MetadataService constructor
     */
    protected function __construct()
    {

    }


    /**
     * @param ilObject $object
     * @param string   $field_group_id
     * @param string   $field_id
     * @param          $value
     */
    public function setValue(ilObject $object, string $field_group_id, string $field_id, $value)
    {
        $record = $this->getRecord($object, $field_group_id, $field_id);
        if(!is_object($record)) { return; }
        $record->setValue($value);
        $record->save();
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
        $fieldGroup = $this->getFieldGroupByIdentifier($field_group_id);
        $field = $this->getFieldByIdentifier($field_id);
        if(!is_object($field)) {return null;}
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


    /**
     * @param int $field_group_id
     *
     * @return FieldGroup|null
     */
    public function getFieldGroupById(int $field_group_id)/*: ?FieldGroup*/
    {
        return FieldGroup::where([
            "id" => $field_group_id
        ])->first();
    }


    /**
     * @param string $field_group_identifier
     *
     * @return FieldGroup|null
     */
    public function getFieldGroupByIdentifier(string $field_group_identifier)/*: ?FieldGroup*/
    {
        return FieldGroup::findByIdentifier($field_group_identifier);
    }


    /**
     * @param int $field_id
     *
     * @return Field|null
     */
    public function getFieldById(int $field_id)/*: ?Field*/
    {
        return NullField::where([
            "id" => $field_id
        ])->first();
    }


    /**
     * @param string $field_identifier
     *
     * @return Field|null
     */
    public function getFieldByIdentifier(string $field_identifier)/*: ?Field*/
    {
        return Field::findByIdentifier($field_identifier);
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
     * Return the MetaData FieldGroups mapped to the given object type
     *
     * @param string $obj_type
     *
     * @return ilObjectMapping[]
     */
    public function getMappings(string $obj_type) : array
    {
        static $cache = array();
        if (isset($cache[$obj_type])) {
            return $cache[$obj_type];
        }
        $mappings = ilObjectMapping::where(array(
            'obj_type' => $obj_type,
            'active'   => 1,
        ))->get();
        $cache[$obj_type] = $mappings;

        return $mappings;
    }


    /**
     * @param ConsumerObject  $object
     * @param ilObjectMapping $mapping
     * @param int             $show_context
     *
     * @return bool
     */
    public function canBeShow(ConsumerObject $object, ilObjectMapping $mapping, int $show_context) : bool
    {
        static $cache = [];

        $cache_id = implode("_", [$object->getRefId(), $mapping->getId(), $show_context]);

        if (isset($cache[$cache_id])) {
            return $cache[$cache_id];
        }

        if (!$object->getRefId()) {
            return ($cache[$cache_id] = false);
        }

        if (!self::dic()->access()->checkAccess('read', '', $object->getRefId())) {
            return ($cache[$cache_id] = false);
        }

        if (!$mapping->isActive()) {
            return ($cache[$cache_id] = false);
        }

        if ($mapping->getObjType() !== $object->getType()) {
            return ($cache[$cache_id] = false);
        }

        switch ($show_context) {
            case self::SHOW_CONTEXT_EDIT_IN_TAB:
                if (!$mapping->isEditable()) {
                    return ($cache[$cache_id] = false);
                }

                if (!self::dic()->access()->checkAccess('write', '', $object->getRefId())) {
                    return ($cache[$cache_id] = false);
                }
                break;

            case self::SHOW_CONTEXT_SHOW_RIGHT_BLOCK:
                if (!$mapping->isShowBlock()) {
                    return ($cache[$cache_id] = false);
                }
                break;

            case self::SHOW_CONTEXT_SHOW_INFO_SCREEN:
                if (!$mapping->isShowInfoScreen()) {
                    return ($cache[$cache_id] = false);
                }
                break;

            default:
                return ($cache[$cache_id] = false);
        }

        if (!$mapping->isOnlyCertainPlaces()) {
            return ($cache[$cache_id] = true);
        }

        $parent_ref_id = intval(self::dic()->tree()->getParentId($object->getRefId()));

        if ($mapping->getOnlyCertainPlacesRefId() === $parent_ref_id) {
            return ($cache[$cache_id] = true);
        }

        if ($mapping->isOnlyCertainPlacesWholeTree()) {
            return ($cache[$cache_id] = in_array($parent_ref_id, self::dic()->tree()->getSubTree(self::dic()->tree()->getNodeData($mapping->getOnlyCertainPlacesRefId()), false)));
        }

        return ($cache[$cache_id] = false);
    }


    /**
     * @return FieldGroup[]
     */
    public function getFieldGroups()
    {
        $field_groups = [];

        foreach (FieldGroup::orderBy('identifier')->get() as $field_group) {
            $field_groups[$field_group->getIdentifier()] = $field_group;
        }

        return $field_groups;
    }


    /**
     * @param ilObject $object
     *
     * @return Record[]
     */
    public function getRecordsForObject(ilObject $object) : array
    {
        return Record::where([
            "obj_type" => $object->getType(),
            "obj_id"   => $object->getId()
        ])->get();
    }


    /**
     * @param ilObject $old_obj
     * @param ilObject $new_obj
     */
    public function cloneMetadata(ilObject $old_obj, ilObject $new_obj)/*:void*/
    {
        /**
         * @var Record $record
         */
        foreach ($this->getRecordsForObject($old_obj) as $record) {
            $group = $this->getFieldGroupById($record->getFieldGroupId());
            if (!$group) {
                continue;
            }

            $field = $this->getFieldById($record->getFieldId());
            if (!$field) {
                continue;
            }

            $this->setValue($new_obj, $group->getIdentifier(), $field->getIdentifier(), $this->getValue($old_obj, $group->getIdentifier(), $field->getIdentifier()));
        };
    }
}