<?php

namespace SRAG\ILIAS\Plugins\MetaData\Object;

class ilConsumerObject implements ConsumerObject
{

    /**
     * @var \ilObject
     */
    protected $object;


    /**
     * @param \ilObject $object
     */
    public function __construct(\ilObject $object)
    {
        $this->object = $object;
    }


    /**
     * @return int
     */
    public function getId()
    {
        return $this->object->getId();
    }


    /**
     * @return string
     */
    public function getType()
    {
        return $this->object->getType();
    }


    /**
     * @inheritDoc
     */
    public function getRefId() : int
    {
        return intval($this->object->getRefId());
    }
}