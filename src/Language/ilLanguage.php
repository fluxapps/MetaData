<?php

namespace SRAG\ILIAS\Plugins\MetaData\Language;

/**
 * Class ilLanguage
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\MetaData\Language
 */
class ilLanguage implements Language
{

    /**
     * @inheritdoc
     */
    public function getAvailableLanguages()
    {
        global $lng;
        static $languages = null;
        if (is_array($languages)) {
            return $languages;
        }
        $languages = $lng->getInstalledLanguages();
        // Move default language to first position
        $key = array_search($this->getDefaultLanguage(), $languages);
        unset ($languages[$key]);
        array_unshift($languages, $this->getDefaultLanguage());

        return $languages;
    }


    /**
     * @inheritdoc
     */
    public function getDefaultLanguage()
    {
        global $lng;

        return $lng->getDefaultLanguage();
    }


    /**
     * @inheritdoc
     */
    public function getLanguageOfCurrentUser()
    {
        global $ilUser, $lng;

        // The anonymous user always returns the default lang, also if platform language is switched
        if ($ilUser->getId() == 0 || $ilUser->getId() == 13) {
            return $lng->getLangKey();
        }

        return $ilUser->getLanguage();
    }
}