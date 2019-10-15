<?php
namespace SRAG\ILIAS\Plugins\MetaData\Inputfield;

use ilCustomInputGUI;
use SRAG\ILIAS\Plugins\MetaData\Field\TextField;
use SRAG\ILIAS\Plugins\MetaData\Language\ilLanguage;
use SRAG\ILIAS\Plugins\MetaData\Language\Language;
use SRAG\ILIAS\Plugins\MetaData\Record\Record;

/**
 * Class InputfieldRichtext
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\MetaData\Inputfield
 */
class InputfieldRichtext extends InputfieldTextarea
{


    public function getILIASFormInputs(Record $record)
    {
        $inputs = parent::getILIASFormInputs($record);
        foreach ($inputs as &$input) {
            if ($this->field->options()->isOnlyDisplay()) {
                $input_ = new ilCustomInputGUI($input->getTitle());
                $input_->setHtml($input->getValue());
                $input = $input_;
            } else {
            $input->setUseRte(true);
            }
        }
        return $inputs;
    }

}