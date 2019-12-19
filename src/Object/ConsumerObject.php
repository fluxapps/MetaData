<?php

namespace SRAG\ILIAS\Plugins\MetaData\Object;

/**
 * Interface ConsumerObject
 *
 * Describes objects using the metadata service.
 * Each object must be represented by a type and ID
 *
 * @package SRAG\ILIAS\Plugins\MetaData\Object
 */
interface ConsumerObject
{

    /**
     * @return int
     */
    public function getId();


    /**
     * @return string
     */
    public function getType();


    /**
     * @return int
     */
    public function getRefId() : int;
}