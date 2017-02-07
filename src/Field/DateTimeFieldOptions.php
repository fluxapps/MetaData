<?php
namespace SRAG\ILIAS\Plugins\MetaData\Field;

/**
 * Class DateTimeFieldOptions
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\MetaData\Field
 */
class DateTimeFieldOptions extends FieldOptions
{

    /**
     * @label Show time
     * @description Offers to input the time beside a date
     * @formProperty ilCheckboxInputGUI
     * @var bool
     */
    protected $showTime = false;

    /**
     * @label Date format
     * @description The format used to display a date in the form. Note: If you enable the time, make sure to also include time information, e.g. H:i
     * @formProperty ilTextInputGUI
     * @var string
     */
    protected $dateFormat = 'd.m.Y';


    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->data = array_merge($this->data, array(
            'showTime' => $this->showTime,
            'dateFormat' => $this->dateFormat,
        ), (array) $data);
    }

    /**
     * @return bool
     */
    public function isShowTime()
    {
        return (bool) $this->data['showTime'];
    }

    /**
     * @param bool $showTime
     */
    public function setShowTime($showTime)
    {
        $this->data['showTime'] = (bool) $showTime;
    }

    /**
     * @return string
     */
    public function getDateFormat()
    {
        return $this->data['dateFormat'];
    }

    /**
     * @param bool $dateFormat
     */
    public function setDateFormat($dateFormat)
    {
        $this->data['dateFormat'] = $dateFormat;
    }

}