<?php
namespace SRAG\ILIAS\Plugins\MetaData\Field;

/**
 * Class FieldOptions
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\MetaData\Field
 */
class FieldOptions
{
    /**
     * @label Required
     * @formProperty ilCheckboxInputGUI
     * @var bool
     */
    protected $required = false;

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
            'required' => false,
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

}