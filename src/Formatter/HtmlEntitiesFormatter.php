<?php


namespace SRAG\ILIAS\Plugins\MetaData\Formatter;

use SRAG\ILIAS\Plugins\MetaData\Record\Record;

/**
 * Class HtmlEntitiesFormatter
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\MetaData\Formatter
 */
class HtmlEntitiesFormatter implements Formatter
{

    /**
     * @return string
     */
    public function getTitle()
    {
        return "Apply PHP's htmlentities function";
    }

    public function getInType()
    {
        return 'string';
    }

    public function getOutType()
    {
        return 'string';
    }

    /**
     * @param Record $record
     * @param $value
     * @return mixed
     */
    public function format(Record $record, $value)
    {
        if (is_string($value)) {
            return htmlentities($value, ENT_QUOTES, 'UTF-8');
        }

        return $value;
    }
}