<?php

namespace SRAG\ILIAS\Plugins\MetaData\Field;

/**
 * Class UserFieldOptions
 *
 * @package SRAG\ILIAS\Plugins\MetaData\Field
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class UserFieldOptions extends FieldOptions
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
