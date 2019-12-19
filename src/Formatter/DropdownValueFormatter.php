<?php

namespace SRAG\ILIAS\Plugins\MetaData\Formatter;

use SRAG\ILIAS\Plugins\MetaData\Language\ilLanguage;
use SRAG\ILIAS\Plugins\MetaData\Record\Record;

/**
 * Class DropdownValueFormatter
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\MetaData\Formatter
 */
class DropdownValueFormatter implements Formatter
{

    /**
     * @var ilLanguage
     */
    protected $language;


    public function __construct()
    {
        $this->language = new ilLanguage();
    }


    /**
     * @return string
     */
    public function getTitle()
    {
        return "DropdownField: Display value in the users language, fallback to default language";
    }


    public function getInType()
    {
        return 'int';
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
        $field = $record->getField();
        foreach ($field->getData() as $data) {
            if ($data->getId() == $value) {
                return $data->getValue($this->language->getLanguageOfCurrentUser());
            }
        }

        return '';
    }
}