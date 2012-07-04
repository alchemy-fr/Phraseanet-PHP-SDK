<?php

namespace PhraseanetSDK\Entity;

class RecordCaption extends AbstractEntity implements EntityInterface
{
    protected $metaStructureId;
    protected $name;
    protected $value;

    /**
     * Get the related databox meta field id
     *
     * @return integer
     */
    public function getMetaStructureId()
    {
        return $this->metaStructureId;
    }

    public function setMetaStructureId($metaStructureId)
    {
        $this->metaStructureId = $metaStructureId;
    }

    /**
     * Get the name of the caption
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get the value of the caption
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }
}
