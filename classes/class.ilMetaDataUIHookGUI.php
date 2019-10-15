<?php

use ILIAS\DI\Container;
use srag\DIC\MetaData\DICTrait;
use SRAG\ILIAS\Plugins\MetaData\Field\Field;
use SRAG\ILIAS\Plugins\MetaData\Form\ilObjectMapping;
use SRAG\ILIAS\Plugins\MetaData\Object\ilConsumerObject;
use SRAG\ILIAS\Plugins\MetaData\Record\RecordQuery;

/**
 * Class ilMetaDataUIHookGUI
 */
class ilMetaDataUIHookGUI extends ilUIHookPluginGUI
{
	use DICTrait;
	const PLUGIN_CLASS_NAME = ilMetaDataPlugin::class;

    /**
     * @var ilCtrl
     */
    protected $ctrl;

    /**
     * @var ilAccessHandler
     */
    protected $access;

    /**
     * @var ilObjUser
     */
    protected $user;
    /**
     * @var Container
     */
    protected $dic;

    public function __construct()
    {
        global $ilCtrl, $ilAccess, $ilUser, $DIC;
        $this->ctrl = $ilCtrl;
        $this->access = $ilAccess;
        $this->user = $ilUser;
        $this->dic = $DIC;
    }

    function modifyGUI($a_comp, $a_part, $a_par = array())
    {
        parent::modifyGUI($a_comp, $a_part, $a_par);
        if (!$this->ctrl->getContextObjType() || !$this->ctrl->getContextObjId()) {
            return;
        }
        if (!count($this->getMappings($this->ctrl->getContextObjType()))) {
            return;
        }
        if ($a_part == 'tabs') {
            $this->addObjectMappingTab($a_par['tabs']);
        }
    }

    public function getHTML($a_comp, $a_part, $a_par = array())
    {
        global $tpl;

        if (is_object($tpl)) {
            $tpl->addCss('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/MetaData/templates/css/srmd.css');
        }
        if (!$this->ctrl->getContextObjType() || !$this->ctrl->getContextObjId()) {
            return parent::getHTML($a_comp, $a_part, $a_par);
        }
        if (!count($this->getMappings($this->ctrl->getContextObjType()))) {
            return parent::getHTML($a_comp, $a_part, $a_par);
        }
        // Check if metadata should be displayed in blocks on the right side
        if ($a_comp == 'Services/Container' && $a_part == 'right_column') {
            $html = $this->getRightColumnBoxes();
            return array(
                'mode' => ilUIHookPluginGUI::PREPEND,
                'html' => $html,
            );
        }
//        // Check if metadata should be displayed in the object list GUI
//        static $rendered = false;
//        if ($a_part == 'template_get' && $a_par['tpl_id'] == 'Services/Container/tpl.container_list_item.html' && !$rendered) {
//            /** @var ilTemplate $tpl */
//            $rendered = true;
//            $tpl = $a_par['tpl_obj'];
//            return array(
//                "mode" => ilUIHookPluginGUI::REPLACE,
//                "html" => $tpl->get() . 'blub',
//            );
//        }
        static $rendered = false;
        if ($this->ctrl->getCmdClass() === strtolower(ilInfoScreenGUI::class) && $a_part == 'template_get' && $a_par['tpl_id'] == 'Services/InfoScreen/tpl.infoscreen.html' && !$rendered) {
            $rendered = true;
            return array(
                "mode" => ilUIHookPluginGUI::PREPEND,
                "html" => $this->getInfoScreenHTML(),
            );
        }
        return parent::getHTML($a_comp, $a_part, $a_par);
    }

    /**
     * Prepend metadata fields on info screen
     *
     * @return string
     */
    protected function getInfoScreenHTML()
    {
        require_once('./Services/InfoScreen/classes/class.ilInfoScreenGUI.php');
        $info = new ilInfoScreenGUI(null);
        $mappings = array_filter($this->getMappings($this->ctrl->getContextObjType()), function($mapping) {
            /** @var $mapping ilObjectMapping */
            return ($mapping->isShowInfoScreen());
        });
        $object = ilObjectFactory::getInstanceByObjId($this->ctrl->getContextObjId());
        $query = new RecordQuery(new ilConsumerObject($object));
        foreach ($mappings as $mapping) {
            if (!$this->checkOnlyCertainPlaces($mapping)) {
                continue;
            }
            foreach ($mapping->getFieldGroups() as $group) {
                $records = array_map(function($field_id) use ($query, $group) {
                    $field = Field::find($field_id);
                    return $query->getRecord($group, $field);
                }, $mapping->getShowInfoFieldIds($group->getId()));
                $records = array_filter($records, function($record) { return $record !== null; });
                if (!count($records)) {
                    continue;
                }
                $info->addSection($group->getTitle($this->user->getLanguage()));
                foreach ($records as $record) {
		                $info->addProperty($record->getField()->getLabel($this->user->getLanguage()), $record->getFormattedValue());
                }
            }
        }
        return $info->getHtml();
    }


    /**
     * @return string
     */
    protected function getRightColumnBoxes()
    {
        $mappings = array_filter($this->getMappings($this->ctrl->getContextObjType()), function($mapping) {
            /** @var $mapping ilObjectMapping */
            return ($mapping->isShowBlock());
        });
        if (!count($mappings)) {
            return '';
        }
        $out = '';
        $object = ilObjectFactory::getInstanceByObjId($this->ctrl->getContextObjId());
        $query = new RecordQuery(new ilConsumerObject($object));
        /** @var ilObjectMapping $mapping */
        foreach ($mappings as $mapping) {
            if (!$this->checkOnlyCertainPlaces($mapping)) {
                continue;
            }
            foreach ($mapping->getFieldGroups() as $group) {
                $records = array_map(function($field_id) use ($query, $group) {
                    $field = Field::find($field_id);
                    return $query->getRecord($group, $field);
                }, $mapping->getShowBlockFieldIds($group->getId()));
                $records = array_filter($records, function($record) { return $record !== null; });
                if (!count($records)) {
                    continue;
                }
                if (self::version()->is54()) {
	                $gui = new srmdBlockGUI54();
                } else {
	                $gui = new srmdBlockGUI53();
                }
                $gui->setTitle($group->getTitle());
                $gui->setData($records);
                $out .= $gui->getHTML();
            }
        }
        return $out;
    }

    /**
     * Add tabs for all object mappings that are editable
     *
     * @param ilTabsGUI $tabs
     */
    protected function addObjectMappingTab(ilTabsGUI $tabs)
    {
        global $tpl;
        // We only add the tab if the user has write access to the current object
        if (!$this->access->checkAccess('write', '', (int)$_GET['ref_id'])) {
            return;
        }
        $mappings = array_filter($this->getMappings($this->ctrl->getContextObjType()), function($mapping) {
            return ($mapping->isEditable());
        });
        static $added = false;
        foreach ($mappings as $mapping) {
            if (!$this->checkOnlyCertainPlaces($mapping)) {
                continue;
            }
            /** @var $mapping ilObjectMapping */
            $this->ctrl->setParameterByClass(srmdGUI::class, 'ref_id', (int)$_GET['ref_id']);
            $this->ctrl->setParameterByClass(srmdGUI::class, 'mapping_id', $mapping->getId());
            $link = $this->ctrl->getLinkTargetByClass(array(ilUIPluginRouterGUI::class, srmdGUI::class), srmdGUI::CMD_SHOW);
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

    /**
     * Return the MetaData FieldGroups mapped to the given object type
     *
     * @param string $obj_type
     * @return ilObjectMapping[]
     */
    protected function getMappings($obj_type)
    {
        static $cache = array();
        if (isset($cache[$obj_type])) {
            return $cache[$obj_type];
        }
        $mappings = ilObjectMapping::where(array(
            'obj_type' => $obj_type,
            'active' => 1,
        ))->get();
        $cache[$obj_type] = $mappings;
        return $mappings;
    }


    /**
     * @param ilObjectMapping $mapping
     *
     * @return bool
     */
    protected function checkOnlyCertainPlaces(ilObjectMapping $mapping) : bool
    {
        if (!$mapping->isOnlyCertainPlaces()) {
            return true;
        }

        $ref_id = intval(filter_input(INPUT_GET, 'ref_id'));
        $parent_ref_id = intval($this->dic->repositoryTree()->getParentId($ref_id));

        if ($mapping->getOnlyCertainPlacesRefId() === $parent_ref_id) {
            return true;
        }

        if ($mapping->isOnlyCertainPlacesWholeTree()) {
            return in_array($parent_ref_id, $this->dic->repositoryTree()->getSubTree(self::dic()->tree()->getNodeData($mapping->getOnlyCertainPlacesRefId()), false));
        }

        return false;
    }
}