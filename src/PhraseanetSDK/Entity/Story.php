<?php

namespace PhraseanetSDK\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class Story extends AbstractEntity implements EntityInterface
{
    protected $storyId;
    protected $databoxId;
    protected $updatedOn;
    protected $createdOn;
    protected $collectionId;
    protected $uuid;
    protected $thumbnail;
    
    protected $records;
    
    protected $metadatas;

    /**
     * Get the record id
     *
     * @return integer
     */
    public function getStoryId()
    {
        return $this->storyId;
    }

    public function setStoryId($storyId)
    {
        $this->storyId = $storyId;
    }

    /**
     * Get the databox id
     *
     * @return integer
     */
    public function getDataboxId()
    {
        return $this->databoxId;
    }

    public function setDataboxId($databoxId)
    {
        $this->databoxId = $databoxId;
    }

    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    public function setThumbnail(Subdef $thumbnail)
    {
        $this->thumbnail = $thumbnail;
    }
    
    /**
     * Last updated date
     *
     * @return \DateTime
     */
    public function getUpdatedOn()
    {
        return $this->updatedOn;
    }

    public function setUpdatedOn(\DateTime $updatedOn)
    {
        $this->updatedOn = $updatedOn;
    }

    /**
     * Creation date
     *
     * @return \DateTime
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    public function setCreatedOn(\DateTime $createdOn)
    {
        $this->createdOn = $createdOn;
    }

    /**
     * Get the record collection id
     *
     * @return integer
     */
    public function getCollectionId()
    {
        return $this->collectionId;
    }

    public function setCollectionId($collectionId)
    {
        $this->collectionId = $collectionId;
    }
    
    /**
     * Get the record UUID
     *
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    public function setUuid($uuid)
    {
        $this->uuid = $uuid;
    }
    
    public function getRecords()
    {
        return $this->records;
    }

    public function setRecords(ArrayCollection $records)
    {
        $this->records = $records;
    }

    public function getMetadatas()
    {
        return $this->metadatas;
    }

    public function setMetadatas(ArrayCollection $metadatas)
    {
        $this->metadatas = $metadatas;
    }


}
