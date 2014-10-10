<?php

/*
 * This file is part of Phraseanet SDK.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhraseanetSDK\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use PhraseanetSDK\Annotation\ApiField as ApiField;
use PhraseanetSDK\Annotation\ApiRelation as ApiRelation;

class Story
{
    /**
     * @ApiField(bind_to="story_id", type="int")
     */
    protected $storyId;
    /**
     * @ApiField(bind_to="databox_id", type="int")
     */
    protected $databoxId;
    /**
     * @ApiField(bind_to="updated_on", type="date")
     */
    protected $updatedOn;
    /**
     * @ApiField(bind_to="created_on", type="date")
     */
    protected $createdOn;
    /**
     * @ApiField(bind_to="collection_id", type="int")
     */
    protected $collectionId;
    /**
     * @ApiField(bind_to="uuid", type="string")
     */
    protected $uuid;
    /**
     * @ApiField(bind_to="thumbnail")
     * @ApiRelation(type="one_to_one", target_entity="Subdef")
     */
    protected $thumbnail;
    /**
     * @ApiField(bind_to="records", type="relation")
     * @ApiRelation(type="one_to_many", target_entity="Record")
     */
    protected $records;
    /**
     * @ApiField(bind_to="metadatas", type="array")
     */
    protected $metadata;

    /**
     * Get unique id
     *
     * @return string
     */
    public function getId()
    {
        return $this->getDataboxId().'_'.$this->getStoryId();
    }

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

    public function getMetadata()
    {
        return $this->metadata;
    }

    public function setMetadata(ArrayCollection $metadata)
    {
        $this->metadata = $metadata;
    }
}
