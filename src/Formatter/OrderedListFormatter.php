<?php


namespace SRAG\ILIAS\Plugins\MetaData\Formatter;

use SRAG\ILIAS\Plugins\MetaData\Record\Record;

/**
 * Class OrderedListFormatter
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\MetaData\Formatter
 */
class OrderedListFormatter implements Formatter
{

    /**
     * @return string
     */
    public function getTitle()
    {
        return "Display an array of values as ordered list";
    }

    public function getInType()
    {
        return 'array';
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
        if (!is_array($value)) {
            return $value;
        }
        $out = '<ol>';
        foreach ($value as $item) {
            $out .= '<li>' . $item . '</li>';
        }
        $out .= '</ol>';

        return $out;
    }
}