<?php
namespace SRAG\ILIAS\Plugins\MetaData\Formatter;

use SRAG\ILIAS\Plugins\MetaData\Record\Record;

/**
 * Class GoogleMapsFormatter
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\MetaData\Formatter
 */
class GoogleMapsFormatter implements Formatter
{

    /**
     * @return string
     */
    public function getTitle()
    {
        return 'LocationField: Display location in a Google Map';
    }

    /**
     * Return the expected type of the value at input
     *
     * @return string
     */
    public function getInType()
    {
        return 'array';
    }

    /**
     * Return the type of the value at output
     *
     * @return string
     */
    public function getOutType()
    {
        return 'string';
    }

    /**
     * @param Record $record
     * @param $value
     * @return mixed
     */
    public function format(Record $record, $value)
    {
        include_once("./Services/Maps/classes/class.ilMapUtil.php");
        $map_gui = \ilMapUtil::getMapGUI();
        $map_gui->setMapId("map_" . uniqid())//
        ->setLatitude($value['lat'])
            ->setLongitude($value['long'])
            ->setZoom($value['zoom'])
            ->setEnableTypeControl(true)
            ->setEnableLargeMapControl(true)
            ->setEnableUpdateListener(false)
            ->setEnableCentralMarker(true);

        return $map_gui->getHTML();
    }
}