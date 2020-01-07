<?php

namespace SRAG\ILIAS\Plugins\MetaData\Formatter;

use ilMetaDataPlugin;
use srag\DIC\MetaData\DICTrait;
use SRAG\ILIAS\Plugins\MetaData\Record\Record;

/**
 * Class ObjectTitleFormatter
 *
 * @package SRAG\ILIAS\Plugins\MetaData\Formatter
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ObjectTitleFormatter implements Formatter
{

    use DICTrait;
    const PLUGIN_CLASS_NAME = ilMetaDataPlugin::class;


    /**
     * ObjectTitleFormatter constructor
     */
    public function __construct()
    {

    }


    /**
     * @inheritDoc
     */
    public function getTitle() : string
    {
        return "Object title";
    }


    /**
     * @inheritDoc
     */
    public function getInType() : string
    {
        return "int[]|int";
    }


    /**
     * @inheritDoc
     */
    public function getOutType() : string
    {
        return "string";
    }


    /**
     * @inheritDoc
     */
    public function format(Record $record, $value) : string
    {
        if (is_array($value)) {
            return nl2br(implode("\n", array_map(function (int $org_unit_ref_id) : string {
                return self::dic()->objDataCache()->lookupTitle(self::dic()->objDataCache()->lookupObjId($org_unit_ref_id));
            }, $value)), false);
        } else {
            return self::dic()->objDataCache()->lookupTitle(self::dic()->objDataCache()->lookupObjId($value));
        }
    }
}
