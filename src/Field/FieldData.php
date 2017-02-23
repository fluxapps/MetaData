<?php
namespace SRAG\ILIAS\Plugins\MetaData\Field;

/**
 * Interface FieldData
 * @package SRAG\ILIAS\Plugins\MetaData\Field
 */
interface FieldData
{
    /**
     * Get a unique ID of the FieldData
     *
     * @return string
     */
    public function getId();

    /**
     * Get the value of the FieldData in the given language.
     * If $lang is empty, returns the value in the default language
     *
     * @param string $lang
     * @return string
     */
    public function getValue($lang = '');

    /**
     * Return the sorting relative to other FieldData of the same set
     *
     * @return int
     */
    public function getSort();

}