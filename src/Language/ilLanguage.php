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
        global $ilUser;

        return $ilUser->getLanguage();
    }


}