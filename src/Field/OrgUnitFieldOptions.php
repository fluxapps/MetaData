<?php

namespace SRAG\ILIAS\Plugins\MetaData\Field;

/**
 * Class OrgUnitFieldOptions
 *
 * @package SRAG\ILIAS\Plugins\MetaData\Field
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class OrgUnitFieldOptions extends IntegerFieldOptions
{

    /**
     * @inheritDoc
     */
    public function __construct(array $data = [])
    {
        parent::__construct($data);

        $this->data = array_merge($this->data, [], $data);
    }
}
