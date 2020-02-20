<?php

namespace SRAG\ILIAS\Plugins\MetaData\Form;

use SRAG\ILIAS\Plugins\MetaData\Exception\Exception;
use SRAG\ILIAS\Plugins\MetaData\Field\Field;
use SRAG\ILIAS\Plugins\MetaData\Field\FieldGroup;
use SRAG\ILIAS\Plugins\MetaData\Inputfield\Inputfield;
use SRAG\ILIAS\Plugins\MetaData\Language\ilLanguage;
use SRAG\ILIAS\Plugins\MetaData\Language\Language;
use SRAG\ILIAS\Plugins\MetaData\Object\ConsumerObject;
use SRAG\ILIAS\Plugins\MetaData\Record\Record;
use SRAG\ILIAS\Plugins\MetaData\Record\RecordQuery;

/**
 * Class FormAdapter
 *
 * Adapter to add metadata fields from a consumer object to a given form.
 * Use FormAdapter::addFields() to add the fields of a field group to your form
 * Use FormAdapter::saveRecords() to save metadata records with the values from the form
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\MetaData\Form
 */
class FormAdapter
{

    /**
     * @var ConsumerObject
     */
    protected $object;
    /**
     * @var string
     */
    protected $lang;
    /**
     * Stores field IDs of fields not being added, so we can skip them when saving records
     *
     * @var array
     */
    protected $ignored_fields = array();
    /**
     * @var \ilPropertyFormGUI
     */
    protected $form;
    /**
     * @var FieldGroup[]
     */
    protected $groups = array();
    /**
     * @var array
     */
    protected $errors = array();
    /**
     * @var Language
     */
    protected $language;


    /**
     * @param \ilPropertyFormGUI $form
     * @param ConsumerObject     $object
     * @param string             $lang Language code which is used to display data of fields and groups
     */
    public function __construct(\ilPropertyFormGUI $form, ConsumerObject $object, $lang = '')
    {
        $this->form = $form;
        $this->object = $object;
        $this->lang = $lang;
        $this->language = new ilLanguage();
    }


    /**
     * @param FieldGroup    $group
     * @param \Closure|null $onFieldAdd
     */
    public function addFields(FieldGroup $group, \Closure $onFieldAdd = null)
    {
        if (in_array($group, $this->groups)) {
            return;
        }
        $this->groups[] = $group;
        $this->addSection($group);
        foreach ($group->getFields() as $field) {
            if ($onFieldAdd !== null) {
                $add = $onFieldAdd($field);
                if ($add) {
                    $this->addField($group, $field);
                } else {
                    $this->ignore($group, $field);
                }
            } else {
                $this->addField($group, $field);
            }
        }
    }


    /**
     * @param FieldGroup $group
     */
    protected function addSection(FieldGroup $group)
    {
        $header = new \ilFormSectionHeaderGUI();
        $header->setTitle($group->getTitle($this->lang));
        if ($group->getDescription($this->lang)) {
            $header->setInfo($group->getDescription($this->lang));
        }
        $this->form->addItem($header);
    }


    /**
     * @param FieldGroup $group
     * @param Field      $field
     */
    protected function addField(FieldGroup $group, Field $field)
    {
        $class = $field->getInputfieldClass();
        /** @var Inputfield $inputfield */
        $inputfield = new $class($field, $this->lang);
        $record = $this->getRecord($group, $field);
        $inputs = $inputfield->getILIASFormInputs($record);
        foreach ($inputs as $input) {
            /** @var \ilFormPropertyGUI $input */
            $this->form->addItem($input);
        }
    }


    /**
     * @param FieldGroup $group
     * @param Field      $field
     *
     * @return Record
     */
    protected function getRecord(Fieldgroup $group, Field $field)
    {
        $query = new RecordQuery($this->object);
        $record = $query->getRecord($group, $field);
        if (!$record) {
            $record = new Record();
            $record->setFieldGroupId($group->getId());
            $record->setFieldId($field->getId());
            $record->setObjType($this->object->getType());
            $record->setObjId($this->object->getId());
        }

        return $record;
    }


    /**
     * @param FieldGroup $group
     * @param Field      $field
     */
    protected function ignore(FieldGroup $group, Field $field)
    {
        $this->ignored_fields[] = $group->getId() . '-' . $field->getId();
    }


    /**
     * @param \Closure|null $onSaveRecord
     *
     * @return bool
     */
    public function saveRecords(\Closure $onSaveRecord = null)
    {
        foreach ($this->groups as $group) {
            foreach ($group->getFields() as $field) {
                if ($field->options()->isOnlyDisplay()) {
                    continue;
                }
                if ($onSaveRecord !== null) {
                    $save = $onSaveRecord($group, $field);
                    if ($save && !$this->isIgnored($group, $field)) {
                        $this->saveRecord($group, $field);
                    }
                } else {
                    $this->saveRecord($group, $field);
                }
            }
        }

        return (count($this->errors) == 0);
    }


    /**
     * @param FieldGroup $group
     * @param Field      $field
     *
     * @return bool
     */
    protected function isIgnored(FieldGroup $group, Field $field)
    {
        return in_array($group->getId() . '-' . $field->getId(), $this->ignored_fields);
    }


    /**
     * @param FieldGroup $group
     * @param Field      $field
     */
    protected function saveRecord(FieldGroup $group, Field $field)
    {
        global $ilAppEventHandler;

        $class = $field->getInputfieldClass();
        /** @var Inputfield $inputfield */
        $inputfield = new $class($field, $this->lang);
        $record = $this->getRecord($group, $field);
        $old_value = $record->getValue();
        $record->setValue($inputfield->getRecordValue($record, $this->form));
        try {
            $record->save();

            $ilAppEventHandler->raise(IL_COMP_PLUGIN . '/MetaData',
                'aftersave',
                array(
                    'group'    => $group,
                    'field'    => $field,
                    'old_value' => $old_value,
                    'record'   => $record,
                    'obj_type' => $record->getObjType(),
                    'obj_id'   => $record->getObjId()
                ));
        } catch (Exception $e) {
            $this->errors[] = new Error($record, $e);
        }
    }


    /**
     * @return Error[]
     */
    public function getErrors()
    {
        return $this->errors;
    }
}

/**
 * Small container class storing errors when saving records with the FormAdapter.
 * Holds the record where persisting data failed together with the thrown exception message.
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\MetaData\Form
 */
class Error
{

    /**
     * @var Exception
     */
    public $exception;
    /**
     * @var Record
     */
    public $record;


    /**
     * @param Record    $record
     * @param Exception $exception
     */
    public function __construct(Record $record, Exception $exception)
    {
        $this->exception = $exception;
        $this->record = $record;
    }
}