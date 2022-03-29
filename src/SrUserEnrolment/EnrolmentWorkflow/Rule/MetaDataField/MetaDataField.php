<?php

namespace SRAG\ILIAS\Plugins\MetaData\SrUserEnrolment\EnrolmentWorkflow\Rule\MetaDataField;

use ilMetaDataPlugin;
use srag\DIC\MetaData\DICStatic;
use srag\Plugins\SrUserEnrolment\EnrolmentWorkflow\Rule\AbstractRule;
use srag\Plugins\SrUserEnrolment\EnrolmentWorkflow\Rule\Fields\Field\Field;
use srag\Plugins\SrUserEnrolment\EnrolmentWorkflow\Rule\Fields\Operator\Operator;
use srag\Plugins\SrUserEnrolment\EnrolmentWorkflow\Rule\Fields\Value\Value;
use srag\Plugins\SrUserEnrolment\EnrolmentWorkflow\Rule\RulesGUI;

/**
 * Class MetaDataField
 *
 * @package SRAG\ILIAS\Plugins\MetaData\SrUserEnrolment\EnrolmentWorkflow\Rule\MetaDataField
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class MetaDataField extends AbstractRule
{

    use Field;
    use Operator;
    use Value;
    const TABLE_NAME_SUFFIX = "mtdtfld";
    /**
     * @var string
     *
     * @con_has_field    true
     * @con_fieldtype    text
     * @con_is_notnull   true
     */
    protected $field_group = "";


    /**
     * @inheritDoc
     */
    public static function supportsParentContext(/*?*/ int $parent_context = null) : bool
    {
        switch ($parent_context) {
            case self::PARENT_CONTEXT_COURSE:
                return false;

            default:
                return true;
        }
    }


    /**
     * @inheritDoc
     */
    public function getRuleTypeTitle() : string
    {
        return DICStatic::plugin(ilMetaDataPlugin::class)->translate("rule_type_" . self::getRuleType(), RulesGUI::LANG_MODULE);
    }


    /**
     * @inheritDoc
     */
    public function getRuleDescription() : string
    {
        $descriptions = [];

        $descriptions[] = $this->field_group . " " . $this->field . " " . $this->getOperatorTitle() . "  " . $this->value;

        return nl2br(implode("\n", $descriptions), false);
    }


    /**
     * @inheritDoc
     */
    public function sleep(/*string*/ $field_name)
    {
        $field_value = $this->{$field_name};

        $field_value_operator = $this->sleepOperator($field_name, $field_value);
        if ($field_value_operator !== null) {
            return $field_value_operator;
        }

        switch ($field_name) {
            default:
                return parent::sleep($field_name);
        }
    }


    /**
     * @inheritDoc
     */
    public function wakeUp(/*string*/ $field_name, $field_value)
    {
        $field_value_operator = $this->wakeUpOperator($field_name, $field_value);
        if ($field_value_operator !== null) {
            return $field_value_operator;
        }

        switch ($field_name) {
            default:
                return parent::wakeUp($field_name, $field_value);
        }
    }


    /**
     * @return string
     */
    public function getFieldGroup() : string
    {
        return $this->field_group;
    }


    /**
     * @param string $field_group
     */
    public function setFieldGroup(string $field_group)/* : void*/
    {
        $this->field_group = $field_group;
    }
}
