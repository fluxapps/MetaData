<?php


namespace SRAG\ILIAS\Plugins\MetaData\Formatter;

use SRAG\ILIAS\Plugins\MetaData\Record\Record;

/**
 * Class UnorderedListFormatter
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\MetaData\Formatter
 */
class UnorderedListFormatter implements Formatter
{

    /**
     * @return string
     */
    public function getTitle()
    {
        return "Display an array of values as unordered list";
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
        $out = '<ul>';
        foreach ($value as $item) {
            $out .= '<li>' . $item . '</li>';
        }
        $out .= '</ul>';
        return $out;
    }
}