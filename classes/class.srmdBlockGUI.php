<?php
require_once('./Services/Block/classes/class.ilBlockGUI.php');

/**
 * Class srmdBlockGUI
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 */
class srmdBlockGUI extends ilBlockGUI
{
    public function __construct()
    {
        parent::ilBlockGUI();
        $this->setRowTemplate("tpl.srmd_block_row.html", "Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/MetaData");
    }

    function fillRow($record)
    {
        global $ilUser;
        if (!$record) {
            return;
        }
        /** @var $record \SRAG\ILIAS\Plugins\MetaData\Record\Record */
        $this->tpl->setVariable("SRMD_LABEL", $record->getField()->getLabel($ilUser->getLanguage()));
        $this->tpl->setVariable("SRMD_VALUE", $record->getFormattedValue());
    }


    static function getBlockType()
    {
        return 'srmd';
    }

    static function isRepositoryObject()
    {
        return false;
    }
}