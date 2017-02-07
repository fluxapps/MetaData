<?php
namespace SRAG\ILIAS\Plugins\MetaData\Formatter;

use SRAG\ILIAS\Plugins\MetaData\Language\ilLanguage;
use SRAG\ILIAS\Plugins\MetaData\Record\Record;

/**
 * Class MultiDropdownValueFormatter
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\MetaData\Formatter
 */
class MultiDropdownValueFormatter implements Formatter
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
        return "MultiDropdownField: Display values in the users language, fallback to default language";
    }

    public function getInType()
    {
        return 'array[int]';
    }

    public function getOutType()
    {
        return 'array[string]';
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
        $out = array();
        $field = $record->getField();
        foreach ($field->getData() as $data) {
            foreach ($value as $id) {
                if ($data->getId() == $id) {
                    $out[$id] = $data->getValue($this->language->getLanguageOfCurrentUser());
                }
            }
        }

        return $out;
    }
}