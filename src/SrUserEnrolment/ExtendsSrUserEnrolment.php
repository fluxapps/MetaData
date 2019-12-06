<?php

namespace SRAG\ILIAS\Plugins\MetaData\SrUserEnrolment;

use ilMetaDataPlugin;
use srag\DIC\MetaData\DICTrait;
use SRAG\ILIAS\Plugins\MetaData\SrUserEnrolment\EnrolmentWorkflow\Rule\MetaDataField\MetaDataField;
use srag\Plugins\SrUserEnrolment\Utils\SrUserEnrolmentTrait;

/**
 * Class ExtendsSrUserEnrolment
 *
 * @package SRAG\ILIAS\Plugins\MetaData\SrUserEnrolment
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ExtendsSrUserEnrolment
{

    use DICTrait;
    use SrUserEnrolmentTrait;
    const PLUGIN_CLASS_NAME = ilMetaDataPlugin::class;
    /**
     * @var self
     */
    protected static $instance = null;


    /**
     * @return self
     */
    public static function getInstance() : self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    /**
     * ExtendsSrUserEnrolment constructor
     */
    private function __construct()
    {

    }


    /**
     *
     */
    public function handleExtends()
    {
        self::srUserEnrolment()->enrolmentWorkflow()->rules()->factory()->addClass(MetaDataField::class);
    }
}
