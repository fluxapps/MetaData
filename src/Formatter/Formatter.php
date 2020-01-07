<?php

namespace SRAG\ILIAS\Plugins\MetaData\Formatter;

use SRAG\ILIAS\Plugins\MetaData\Field\Field;
use SRAG\ILIAS\Plugins\MetaData\Record\Record;

/**
 * Interface Formatter
 *
 * Format the value of a record when using Record::getFormattedValue()
 * Formatters are defined on field level and can be stacked.
 *
 * @package SRAG\ILIAS\Plugins\MetaData\Formatter
 */
interface Formatter
{

    /**
     * @return string
     */
    public function getTitle();


    /**
     * Return the expected type of the value at input
     *
     * @return string
     */
    public function getInType();


    /**
     * Return the type of the value at output
     *
     * @return string
     */
    public function getOutType();


    /**
     * @param Record $record
     * @param        $value
     *
     * @return mixed
     */
    public function format(Record $record, $value);
}