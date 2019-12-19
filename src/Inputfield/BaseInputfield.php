<?php

namespace SRAG\ILIAS\Plugins\MetaData\Inputfield;

use ilMetaDataPlugin;
use srag\DIC\MetaData\DICTrait;
use SRAG\ILIAS\Plugins\MetaData\Field\Field;
use SRAG\ILIAS\Plugins\MetaData\Record\Record;

/**
 * Class BaseInputfield
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\MetaData\Inputfield
 */
abstract class BaseInputfield implements Inputfield
{

    use DICTrait;
    const PLUGIN_CLASS_NAME = ilMetaDataPlugin::class;
    /**
     * @var Field
     */
    protected $field;
    /**
     * @var string
     */
    protected $lang;


    public function __construct(Field $field, $lang = '')
    {
        $this->field = $field;
        $this->lang = $lang;
    }


    protected function getPostVar(Record $record)
    {
        return implode('_', array(
            'srmd',
            $record->getObjType(),
            $record->getObjId(),
            $record->getFieldGroupId(),
            $record->getFieldId(),
        ));
    }
}