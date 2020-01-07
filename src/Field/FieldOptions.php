<?php

namespace SRAG\ILIAS\Plugins\MetaData\Field;

/**
 * Class FieldOptions
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\MetaData\Field
 */
class FieldOptions
{

    /**
     * @label        Required
     * @formProperty ilCheckboxInputGUI
     * @var bool
     */
    protected $required = false;
    /**
     * @label        Only display
     * @description  Make field not editable, only display it
     * @formProperty ilCheckboxInputGUI
     * @var bool
     */
    protected $only_display = false;
    /**
     * @var array
     */
    protected $data = array();


    /**
     * @param array $data
     */
    public function __construct($data = array())
    {
        $this->data = array_merge(array(
            'required'     => false,
            "only_display" => false
        ), (array) $data);
    }


    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }


    /**
     * @param array $data
     */
    public function setData($data)
    {
        $this->data = array_merge($this->data, (array) $data);
    }


    /**
     * @return bool
     */
    public function isRequired()
    {
        return $this->data['required'];
    }


    /**
     * @param bool $required
     */
    public function setRequired($required)
    {
        $this->data['required'] = (bool) $required;
    }


    /**
     * @return bool
     */
    public function isOnlyDisplay() : bool
    {
        return boolval($this->data["only_display"]);
    }


    /**
     * @param bool $only_display
     */
    public function setOnlyDisplay(bool $only_display)
    {
        $this->data["only_display"] = $only_display;
    }
}