<?php

/**
 * Class srmdBlockGUI53
 *
 * @author studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 */
class srmdBlockGUI53 extends srmdBlockGUI
{

    /**
     * @inheritDoc
     */
    static function getBlockType()
    {
        return self::BLOCK_ID;
    }


    /**
     * @inheritDoc
     */
    static function isRepositoryObject()
    {
        return false;
    }
}
