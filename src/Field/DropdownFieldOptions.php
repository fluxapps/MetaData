<?php

namespace SRAG\ILIAS\Plugins\MetaData\Field;

/**
 * Class DropdownFieldOptions
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\MetaData\Field
 */
class DropdownFieldOptions extends FieldOptions
{

    /**
     * @label        Prepend empty option
     * @description  Adds an empty entry as first option, otherwise the first option is selected
     * @formProperty ilCheckboxInputGUI
     * @var bool
     */
    protected $prependEmptyOption = false;


    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->data = array_merge($this->data, array(
            'prependEmptyOption' => $this->prependEmptyOption,
        ), (array) $data);
    }


    /**
     * @return bool
     */
    public function isPrependEmptyOption()
    {
        return (bool) $this->data['prependEmptyOption'];
    }


    /**
     * @param bool $prependEmptyOption
     */
    public function setPrependEmptyOption($prependEmptyOption)
    {
        $this->data['prependEmptyOption'] = (bool) $prependEmptyOption;
    }
}