<?php
namespace SRAG\ILIAS\Plugins\MetaData\Language;

/**
 * Class ilLanguage
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
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

        return $lng->getInstalledLanguages();
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