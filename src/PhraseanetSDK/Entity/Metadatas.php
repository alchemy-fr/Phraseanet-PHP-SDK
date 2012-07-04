<?php

namespace PhraseanetSDK\Entity;

class Metadatas extends AbstractEntity implements EntityInterface
{
    protected $metaId;
    protected $metaStructureId;
    protected $name;
    protected $value;

    /**
     * Get the metadatas id
     *
     * @return integer
     */
    public function getMetaId()
    {
        return $this->metaId;
    }

    public function setMetaId($id)
    {
        $this->metaId = $id;
    }

    /**
     * Get the related databox meta field id
     *
     * @return integer
     */
    public function getMetaStructureId()
    {
        return $this->metaStructureId;
    }

    public function setMetaStructureId($structureId)
    {
        $this->metaStructureId = $structureId;
    }

    /**
     * Get the meta name
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
     * Get the meta value
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
