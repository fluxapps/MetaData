<?php

namespace SRAG\ILIAS\Plugins\MetaData\Formatter;

use SRAG\ILIAS\Plugins\MetaData\Record\Record;

/**
 * Class Nl2brFormatter
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\MetaData\Formatter
 */
class Nl2brFormatter implements Formatter
{

    /**
     * @return string
     */
    public function getTitle()
    {
        return "Apply PHP's nl2br function";
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
     * @param        $value
     *
     * @return mixed
     */
    public function format(Record $record, $value)
    {
        if (is_string($value)) {
            return nl2br($value);
        }

        return $value;
    }
}