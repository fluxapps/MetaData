<?php

namespace SRAG\ILIAS\Plugins\MetaData\SrUserEnrolment\EnrolmentWorkflow\Rule\MetaDataField;

use ilMetaDataPlugin;
use ilTextInputGUI;
use srag\DIC\MetaData\DICStatic;
use srag\Plugins\SrUserEnrolment\EnrolmentWorkflow\Rule\AbstractRuleFormGUI;
use srag\Plugins\SrUserEnrolment\EnrolmentWorkflow\Rule\Fields\Field\FieldFormGUI;
use srag\Plugins\SrUserEnrolment\EnrolmentWorkflow\Rule\Fields\Operator\OperatorFormGUI;
use srag\Plugins\SrUserEnrolment\EnrolmentWorkflow\Rule\Fields\Value\ValueFormGUI;
use srag\Plugins\SrUserEnrolment\EnrolmentWorkflow\Rule\RuleGUI;

/**
 * Class MetaDataFieldFormGUI
 *
 * @package SRAG\ILIAS\Plugins\MetaData\SrUserEnrolment\EnrolmentWorkflow\Rule\MetaDataField
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class MetaDataFieldFormGUI extends AbstractRuleFormGUI
{

    use FieldFormGUI;
    use OperatorFormGUI;
    use ValueFormGUI;
    /**
     * @var MetaDataField
     */
    protected $rule;


    /**
     * @inheritDoc
     */
    public function __construct(RuleGUI $parent, MetaDataField $rule)
    {
        parent::__construct($parent, $rule);
    }


    /**
     * @inheritDoc
     */
    protected function initFields()/*:void*/
    {
        parent::initFields();

        $this->fields = array_merge(
            $this->fields,
            [
                "field_group" => [
                    self::PROPERTY_CLASS    => ilTextInputGUI::class,
                    self::PROPERTY_REQUIRED => true,
                    "setTitle"              => DICStatic::plugin(ilMetaDataPlugin::class)->translate("field_group", self::LANG_MODULE)
                ]
            ],
            $this->getFieldFormFields(),
            $this->getOperatorFormFields1(),
            $this->getValueFormFields(),
            $this->getOperatorFormFields2()
        );
    }
}
