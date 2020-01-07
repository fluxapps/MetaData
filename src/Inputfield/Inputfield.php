<?php

namespace SRAG\ILIAS\Plugins\MetaData\Inputfield;

use SRAG\ILIAS\Plugins\MetaData\Record\Record;

/**
 * Interface Inputfield
 *
 * The inputfield connects a metadata field to ILIAS form inputs.
 * Furthermore it also converts the value from the form input back to a value that can be stored on a metadata record.
 *
 * @package SRAG\ILIAS\Plugins\MetaData\Inputfield
 */
interface Inputfield
{

    /**
     * @param Record $record
     *
     * @return \ilFormPropertyGUI
     */
    public function getILIASFormInputs(Record $record);


    /**
     * @param Record             $record
     * @param \ilPropertyFormGUI $form
     *
     * @return mixed
     */
    public function getRecordValue(Record $record, \ilPropertyFormGUI $form);
}