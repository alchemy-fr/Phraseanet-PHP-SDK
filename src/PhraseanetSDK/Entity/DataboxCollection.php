<?php

namespace PhraseanetSDK\Entity;

class DataboxCollection extends AbstractEntity implements EntityInterface
{
    protected $baseId;
    protected $collectionId;
    protected $name;
    protected $recordAmount;

    /**
     * The collection base id
     *
     * @return integer
     */
    public function getBaseId()
    {
        return $this->baseId;
    }

    public function setBaseId($baseId)
    {
        $this->baseId = $baseId;
    }

    /**
     * The collection id
     *
     * @return integer
     */
    public function getCollectionId()
    {
        return $this->collectionId;
    }

    public function setCollectionId($collId)
    {
        $this->collectionId = $collId;
    }

    /**
     * The collection name
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
     * The total record in the collection
     *
     * @return int
     */
    public function getRecordAmount()
    {
        return $this->record_amount;
    }

    public function setRecordAmount($record_amount)
    {
        $this->record_amount = $record_amount;
    }
}
