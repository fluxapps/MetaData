<?php

use srag\DIC\MetaData\Util\LibraryLanguageInstaller;
use SRAG\ILIAS\Plugins\MetaData\Field\ArFieldData;
use SRAG\ILIAS\Plugins\MetaData\Field\FieldGroup;
use SRAG\ILIAS\Plugins\MetaData\Field\NullField;
use SRAG\ILIAS\Plugins\MetaData\Form\ilObjectMapping;
use SRAG\ILIAS\Plugins\MetaData\Record\Record;
use SRAG\ILIAS\Plugins\MetaData\RecordValue\DateTimeRecordValue;
use SRAG\ILIAS\Plugins\MetaData\RecordValue\FloatRecordValue;
use SRAG\ILIAS\Plugins\MetaData\RecordValue\IntegerMultiRecordValue;
use SRAG\ILIAS\Plugins\MetaData\RecordValue\LocationRecordValue;
use SRAG\ILIAS\Plugins\MetaData\RecordValue\StringRecordValue;
use SRAG\ILIAS\Plugins\MetaData\RecordValue\TextRecordValue;
use SRAG\ILIAS\Plugins\MetaData\SrUserEnrolment\EnrolmentWorkflow\Rule\MetaDataField\MetaDataField;
use SRAG\ILIAS\Plugins\MetaData\SrUserEnrolment\ExtendsSrUserEnrolment;
use srag\RemovePluginDataConfirm\MetaData\PluginUninstallTrait;

require_once __DIR__ . "/../vendor/autoload.php";
if (file_exists(__DIR__ . "/../../SrUserEnrolment/vendor/autoload.php")) {
    require_once __DIR__ . "/../../SrUserEnrolment/vendor/autoload.php";
}

/**
 * Class ilMetaDataPlugin
 */
class ilMetaDataPlugin extends ilUserInterfaceHookPlugin {

	use PluginUninstallTrait;
	const PLUGIN_NAME = 'MetaData';
	const PLUGIN_CLASS_NAME = self::class;
	const REMOVE_PLUGIN_DATA_CONFIRM_CLASS_NAME = MetaDataRemoveDataConfirm::class;
	/**
	 * @var ilMetaDataPlugin
	 */
	protected static $instance;


	/**
	 * @return ilMetaDataPlugin
	 */
	public static function getInstance() {
		if (self::$instance === null) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	/**
	 * @return string
	 */
	public function getPluginName() {
		return self::PLUGIN_NAME;
	}


    /**
     * @inheritDoc
     */
    public function handleEvent($a_component, $a_event, $a_parameter) {
        if (file_exists(__DIR__ . "/../../SrUserEnrolment/vendor/autoload.php")) {
            switch ($a_component) {
                case "Plugins/" . ilSrUserEnrolmentPlugin::PLUGIN_NAME:
                    switch ($a_event) {
                        case ilSrUserEnrolmentPlugin::EVENT_EXTENDS_SRUSRENR;
                            ExtendsSrUserEnrolment::getInstance()->handleExtends();
                            break;

                        default:
                            break;
                    }
                    break;

                default:
                    break;
            }
        }
    }


	/**
	 * @inheritdoc
	 */
	public function updateLanguages($a_lang_keys = null) {
		parent::updateLanguages($a_lang_keys);

		LibraryLanguageInstaller::getInstance()->withPlugin(self::plugin())->withLibraryLanguageDirectory(__DIR__
			. "/../vendor/srag/removeplugindataconfirm/lang")->updateLanguages();
	}


	/**
	 * @inheritdoc
	 */
	protected function deleteData()/*: void*/ {
		self::dic()->database()->dropTable(NullField::TABLE_NAME, false);
		self::dic()->database()->dropTable(FieldGroup::TABLE_NAME, false);
		self::dic()->database()->dropTable(ArFieldData::TABLE_NAME, false);
		self::dic()->database()->dropTable(Record::TABLE_NAME, false);
		self::dic()->database()->dropTable(ilObjectMapping::TABLE_NAME, false);
		self::dic()->database()->dropTable(DateTimeRecordValue::TABLE_NAME, false);
		self::dic()->database()->dropTable(IntegerMultiRecordValue::TABLE_NAME, false);
		self::dic()->database()->dropTable(StringRecordValue::TABLE_NAME, false);
		self::dic()->database()->dropTable(TextRecordValue::TABLE_NAME, false);
		self::dic()->database()->dropTable(LocationRecordValue::TABLE_NAME, false);
		self::dic()->database()->dropTable(FloatRecordValue::TABLE_NAME, false);
        if (file_exists(__DIR__ . "/../../SrUserEnrolment/vendor/autoload.php")) {
            self::dic()->database()->dropTable(MetaDataField::getTableName(), false);
        }
	}
}
