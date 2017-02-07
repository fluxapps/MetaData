<?php
namespace SRAG\ILIAS\Plugins\MetaData\Field;

/**
 * Class FloatFieldOptions
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\MetaData\Field
 */
class FloatFieldOptions extends FieldOptions
{

    /**
     * @label Min value
     * @formProperty ilNumberInputGUI
     * @var string
     */
    protected $minValue = '';

    /**
     * @label Max value
     * @formProperty ilNumberInputGUI
     * @var string
     */
    protected $maxValue = '';

    /**
     * @label Number of decimals
     * @formProperty ilNumberInputGUI
     * @var int
     */
    protected $nDecimals = 2;


    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->data = array_merge($this->data, array(
            'minValue' => $this->minValue,
            'maxValue' => $this->maxValue,
            'nDecimals' => $this->nDecimals,
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

    /**
     * @return int
     */
    public function getNDecimals()
    {
        return $this->data['nDecimals'];
    }

    /**
     * @param int $nDecimals
     */
    public function setNDecimals($nDecimals)
    {
        $this->data['nDecimals'] = $nDecimals;
    }
}