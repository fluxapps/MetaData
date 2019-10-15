<?php

use SRAG\ILIAS\Plugins\MetaData\AbstractMetadataGUI;
use SRAG\ILIAS\Plugins\MetaData\Form\ilObjectMapping;
use SRAG\ILIAS\Plugins\MetaData\Object\ilConsumerObject;

/**
 * Class srmdGUI
 *
 * @author            studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author            Stefan Wanzenried <sw@studer-raimann.ch>
 *
 * @ilCtrl_IsCalledBy srmdGUI: ilUIPluginRouterGUI
 */
class srmdGUI extends AbstractMetadataGUI
{

    /**
     * @var ilObject
     */
    protected $object;
    /**
     * @var ilObjectMapping
     */
    protected $mapping;


    /**
     * srmdGUI constructor
     */
    public function __construct()
    {
        self::dic()->ctrl()->saveParameter($this, 'mapping_id');
        self::dic()->ctrl()->saveParameter($this, 'ref_id');
        self::dic()->ctrl()->saveParameterByClass(ilRepositoryGUI::class, 'ref_id');

        $this->object = ilObjectFactory::getInstanceByRefId(filter_input(INPUT_GET, 'ref_id'));
        $this->mapping = ilObjectMapping::findOrFail(filter_input(INPUT_GET, 'mapping_id'));

        parent::__construct(
            [new ilConsumerObject($this->object)],
            [$this->mapping]);
    }


    /**
     * @inheritDoc
     */
    protected function setTabs()
    {
        self::dic()->mainTemplate()->setTitle($this->object->getPresentationTitle());

        self::dic()->mainTemplate()->setDescription($this->object->getLongDescription());

        self::dic()->mainTemplate()->setTitleIcon(ilObject::_getIcon("", "big", $this->object->getType()), self::dic()->language()->txt("obj_" . $this->object->getType()));

        $lgui = ilObjectListGUIFactory::_getListGUIByType($this->object->getType());
        $lgui->initItem($this->object->getRefId(), $this->object->getId());
        self::dic()->mainTemplate()->setAlertProperties($lgui->getAlertProperties());
        self::dic()->locator()->addRepositoryItems();
        self::dic()->mainTemplate()->setLocator();

        parent::setTabs();
    }


    /**
     * @inheritDoc
     */
    protected function checkAccess() : bool
    {
        return self::dic()->access()->checkAccess('write', '', $this->object->getRefId());
    }


    /**
     * @inheritDoc
     */
    protected function back()
    {
        self::dic()->ctrl()->redirectByClass([ilRepositoryGUI::class, get_class(((new ilObjectGUIFactory())->getInstanceByRefId($this->object->getRefId())))]);
    }
}
