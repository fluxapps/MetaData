<?php

namespace SRAG\ILIAS\Plugins\MetaData\Field;

/**
 * Class TextFieldOptions
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\MetaData\Field
 */
class TextFieldOptions extends FieldOptions
{

    /**
     * @label        Regex Validation
     * @description  Form is only valid if the submitted input matches the regular expression
     * @formProperty ilTextInputGUI
     * @var string
     */
    protected $regex = '';
    /**
     * @label        Max length of the text
     * @formProperty ilNumberInputGUI
     * @var int
     */
    protected $maxLength = '';
    /**
     * @label        Language tabs
     * @description  The text fields of different languages are separated via tabs, only available if Multi Language Support is enabled
     * @formProperty ilCheckboxInputGUI
     * @var bool
     */
    protected $languageTabs = true;
    /**
     * @label        Multi Language support
     * @description  Offers to add text for all available languages. If not checked, there is only an input for the default language
     * @formProperty ilCheckboxInputGUI
     * @var bool
     */
    protected $multiLang = true;


    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->data = array_merge($this->data, array(
            'regex'        => $this->regex,
            'maxLength'    => $this->maxLength,
            'multiLang'    => $this->multiLang,
            'languageTabs' => $this->languageTabs,
        ), (array) $data);
    }


    /**
     * @return mixed
     */
    public function getRegex()
    {
        return $this->data['regex'];
    }


    /**
     * @param mixed $regex
     */
    public function setRegex($regex)
    {
        $this->data['regex'] = $regex;
    }


    /**
     * @return mixed
     */
    public function getMaxLength()
    {
        return $this->data['maxLength'];
    }


    /**
     * @param mixed $maxLength
     */
    public function setMaxLength($maxLength)
    {
        $this->data['maxLength'] = $maxLength;
    }


    /**
     * @return bool
     */
    public function isMultiLang()
    {
        return (bool) $this->data['multiLang'];
    }


    /**
     * @param bool $multilang
     */
    public function setMultiLang($multilang)
    {
        $this->data['multiLang'] = (bool) $multilang;
    }


    /**
     * @return mixed
     */
    public function getUseLanguageTabs()
    {
        return (bool) $this->data['languageTabs'];
    }


    /**
     * @param mixed $languageTabs
     */
    public function setLanguageTabs($languageTabs)
    {
        $this->data['languageTabs'] = (bool) $languageTabs;
    }
}