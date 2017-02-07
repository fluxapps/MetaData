<?php
namespace SRAG\ILIAS\Plugins\MetaData\Language;

/**
 * Interface Language
 *
 * @package SRAG\ILIAS\Plugins\MetaData\Language
 */
interface Language
{
    /**
     * Return the available languages
     *
     * @return array
     */
    public function getAvailableLanguages();

    /**
     * Return the default language
     *
     * @return string
     */
    public function getDefaultLanguage();

    /**
     * Returns the language of the current user
     *
     * @return string
     */
    public function getLanguageOfCurrentUser();

}