<?php
use SRAG\ILIAS\Plugins\MetaData\Form\ilObjectMapping;

require_once('./Services/UIComponent/classes/class.ilUIHookPluginGUI.php');

/**
 * Class ilMetaDataUIHookGUI
 */
class ilMetaDataUIHookGUI extends ilUIHookPluginGUI
{

    function modifyGUI($a_comp, $a_part, $a_par = array())
    {
        parent::modifyGUI($a_comp, $a_part, $a_par);
        if ($a_part == 'tabs') {
            $this->addObjectMappingTab($a_par['tabs']);
        }
    }


    protected function addObjectMappingTab(ilTabsGUI $tabs)
    {
        global $ilAccess, $ilCtrl, $tpl;
        if (!$ilCtrl->getContextObjType() || !$ilCtrl->getContextObjId()) {
            return;
        }
        // We only add the tab if the user has write access to the current object
        // TODO Add similar permissions check to mapping like ctrl main menu plugin
        /** @var $ilAccess ilAccessHandler */
        if (!$ilAccess->checkAccess('write', '', (int) $_GET['ref_id'])) {
            return;
        }
        $mappings = ilObjectMapping::where(array(
            'obj_type' => $ilCtrl->getContextObjType(),
            'active' => 1,
        ))->get();
        static $added = false;
        foreach ($mappings as $mapping) {
            /** @var $mapping ilObjectMapping */
            $ilCtrl->setParameterByClass('srmdGUI', 'ref_id', (int) $_GET['ref_id']);
            $ilCtrl->setParameterByClass('srmdGUI', 'back_target', urlencode(base64_encode($_SERVER['REQUEST_URI'])));
            $ilCtrl->setParameterByClass('srmdGUI', 'mapping_id', $mapping->getId());
            $ilCtrl->setParameterByClass('srmdGUI', 'mapping_obj_id', $ilCtrl->getContextObjId());
            $link = $ilCtrl->getLinkTargetByClass(array('ilUIPluginRouterGUI', 'srmdGUI'));
            $tabs->addTab('srmd_mapping_' . $mapping->getId(), $mapping->getTabTitle(), $link);
            if (!$added) {
                $added = true;
                // Hack to not make the tab active -.-
                $tpl->addOnLoadCode("
                    var activeTabs = $('#ilTab li.active'); 
                    if (activeTabs.length > 1) { 
                        activeTabs.each(function(i) { 
                            if (i > 0) $(this).removeClass('active');
                        }); 
                    }");
            }
        }
    }

}