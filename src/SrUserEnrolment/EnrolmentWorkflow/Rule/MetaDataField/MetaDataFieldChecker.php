<?php

namespace SRAG\ILIAS\Plugins\MetaData\SrUserEnrolment\EnrolmentWorkflow\Rule\MetaDataField;

use ilObjectFactory;
use SRAG\ILIAS\Plugins\MetaData\MetadataService;
use srag\Plugins\SrUserEnrolment\EnrolmentWorkflow\Rule\AbstractRuleChecker;
use srag\Plugins\SrUserEnrolment\EnrolmentWorkflow\Rule\Fields\Operator\OperatorChecker;

/**
 * Class MetaDataFieldChecker
 *
 * @package SRAG\ILIAS\Plugins\MetaData\SrUserEnrolment\EnrolmentWorkflow\Rule\MetaDataField
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class MetaDataFieldChecker extends AbstractRuleChecker
{

    use OperatorChecker;
    /**
     * @var MetaDataField
     */
    protected $rule;


    /**
     * @inheritDoc
     */
    public function __construct(MetaDataField $rule)
    {
        parent::__construct($rule);
    }


    /**
     * @inheritDoc
     */
    public function check(int $user_id, int $obj_ref_id) : bool
    {
        $metadata_field_value = MetadataService::getInstance()->getValue(ilObjectFactory::getInstanceByRefId($obj_ref_id, false), $this->rule->getFieldGroup(), $this->rule->getField());

        if (is_array($metadata_field_value)) {
            $metadata_field_value = current($metadata_field_value);
        }

        return $this->checkOperator($metadata_field_value, $this->rule->getValue(), $this->rule->getOperator(), $this->rule->isOperatorNegated(), $this->rule->isOperatorCaseSensitive());
    }


    /**
     * @inheritDoc
     */
    protected function getObjectsUsers() : array
    {
        return [];
    }
}
