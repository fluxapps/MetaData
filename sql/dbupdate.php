<#1>
<?php
require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/MetaData/vendor/autoload.php');

SRAG\ILIAS\Plugins\MetaData\Field\NullField::installDB();
SRAG\ILIAS\Plugins\MetaData\Field\FieldGroup::installDB();
SRAG\ILIAS\Plugins\MetaData\Field\ArFieldData::installDB();
SRAG\ILIAS\Plugins\MetaData\Record\Record::installDB();
SRAG\ILIAS\Plugins\MetaData\Form\ilObjectMapping::installDB();

\SRAG\ILIAS\Plugins\MetaData\RecordValue\DateTimeRecordValue::installDB();
\SRAG\ILIAS\Plugins\MetaData\RecordValue\IntegerMultiRecordValue::installDB();
\SRAG\ILIAS\Plugins\MetaData\RecordValue\IntegerRecordValue::installDB();
\SRAG\ILIAS\Plugins\MetaData\RecordValue\StringRecordValue::installDB();
\SRAG\ILIAS\Plugins\MetaData\RecordValue\TextRecordValue::installDB();
\SRAG\ILIAS\Plugins\MetaData\RecordValue\LocationRecordValue::installDB();
\SRAG\ILIAS\Plugins\MetaData\RecordValue\FloatRecordValue::installDB();
?>
<#2>
<?php
require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/MetaData/vendor/autoload.php');
SRAG\ILIAS\Plugins\MetaData\Form\ilObjectMapping::updateDB();
?>
