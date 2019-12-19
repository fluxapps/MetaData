<?php

namespace SRAG\ILIAS\Plugins\MetaData\Field;

/**
 * Class IntegerFieldOptions
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\MetaData\Field
 */
class IntegerFieldOptions extends FieldOptions
{

    /**
     * @label        Min value
     * @formProperty ilNumberInputGUI
     * @var int
     */
    protected $minValue = '';
    /**
     * @label        Max value
     * @formProperty ilNumberInputGUI
     * @var int
     */
    protected $maxValue = '';


    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->data = array_merge($this->data, array(
            'minValue' => $this->minValue,
            'maxValue' => $this->maxValue,
        ), (array) $data);
    }


    /**
     * @return int
     */
    public function getMinValue()
    {
        return $this->data['minValue'];
    }


    /**
     * @param int $minValue
     */
    public function setMinValue($minValue)
    {
        $this->data['minValue'] = $minValue;
    }


    /**
     * @return int
     */
    public function getMaxValue()
    {
        return $this->data['maxValue'];
    }


    /**
     * @param int $maxValue
     */
    public function setMaxValue($maxValue)
    {
        $this->data['maxValue'] = $maxValue;
    }
}