<?php

require_once __DIR__ . "/../../vendor/autoload.php";

use srag\RemovePluginDataConfirm\MetaData\AbstractRemovePluginDataConfirm;

/**
 * Class MetaDataRemoveDataConfirm
 *
 * @author            studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy MetaDataRemoveDataConfirm: ilUIPluginRouterGUI
 */
class MetaDataRemoveDataConfirm extends AbstractRemovePluginDataConfirm
{

    const PLUGIN_CLASS_NAME = ilMetaDataPlugin::class;
}
