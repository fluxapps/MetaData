<?php

namespace SRAG\ILIAS\Plugins\MetaData\Util;

/**
 * trait SingletonTrait
 *
 * @author Theodor Truffer <tt@studer-raimann.ch>
 */
trait SingletonTrait
{

    /**
     * @var \srag\Plugins\SrEventStorm\Utils\SingletonTrait
     */
    protected static $instance;


    /**
     * @return self
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}