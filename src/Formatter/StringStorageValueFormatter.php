<?php

namespace SRAG\ILIAS\Plugins\MetaData\Formatter;

use SRAG\ILIAS\Plugins\MetaData\Language\ilLanguage;
use SRAG\ILIAS\Plugins\MetaData\Record\Record;

/**
 * Class StringStorageValueFormatter
 *
 * Displays a value from the StringStorage|TextStorage in the users language, with a fallback
 * to the default language, if not available;
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\MetaData\Formatter
 */
class StringStorageValueFormatter implements Formatter
{

    /**
     * @var ilLanguage
     */
    protected $language;


    public function __construct()
    {
        $this->language = new ilLanguage();
    }


    public function getTitle()
    {
        return 'Text(area)Field: Display value in users language, fallback to default language';
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
     * @param        $value
     *
     * @return string
     */
    public function format(Record $record, $value)
    {
        if (!is_array($value)) {
            return $value;
        }
        $lang = $this->language->getLanguageOfCurrentUser();
        if (isset($value[$lang])) {
            return $value[$lang];
        }
        $default = $this->language->getDefaultLanguage();

        return (isset($value[$default])) ? $value[$default] : '';
    }
}