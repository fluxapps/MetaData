<?php
include_once('./Services/UIComponent/classes/class.ilUserInterfaceHookPlugin.php');
require_once(dirname(__DIR__) . '/vendor/autoload.php');

/**
 * Class ilMetaDataPlugin
 */
class ilMetaDataPlugin extends ilUserInterfaceHookPlugin
{

    /**
     * @var ilMetaDataPlugin
     */
    protected static $instance;


    /**
     * @return ilMetaDataPlugin
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    /**
     * @return string
     */
    public function getPluginName()
    {
        return 'MetaData';
    }
}
