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
use JMS\Serializer\Annotation\Expose as Expose;
use JMS\Serializer\Annotation\VirtualProperty as VirtualProperty;
use JMS\Serializer\Annotation\SerializedName as SerializedName;
use JMS\Serializer\Annotation\Type as Type;
use JMS\Serializer\Annotation\ExclusionPolicy as ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class Story
{
    /**
     * @Expose
     * @Type("integer")
     * @ApiField(bind_to="story_id", type="int")
     */
    protected $storyId;
    /**
     * @Expose
     * @Type("integer")
     * @ApiField(bind_to="databox_id", type="int")
     */
    protected $databoxId;
    /**
     * @Expose
     * @Type("DateTime<'Y-m-d H:i:s'>")
     * @ApiField(bind_to="updated_on", type="date")
     */
    protected $updatedOn;
    /**
     * @Expose
     * @Type("DateTime<'Y-m-d H:i:s'>")
     * @ApiField(bind_to="created_on", type="date")
     */
    protected $createdOn;
    /**
     * @Expose
     * @Type("integer")
     * @ApiField(bind_to="collection_id", type="int")
     */
    protected $collectionId;
    /**
     * @Expose
     * @Type("string")
     * @ApiField(bind_to="uuid", type="string")
     */
    protected $uuid;
    /**
     * @Expose
     * @Type("PhraseanetSDK\Entity\Subdef")
     * @ApiField(bind_to="thumbnail", type="relation")
     * @ApiRelation(type="one_to_one", target_entity="Subdef")
     */
    protected $thumbnail;
    /**
     * @Expose
     * @Type("ArrayCollection<PhraseanetSDK\Entity\Record>")
     * @ApiField(bind_to="records", type="relation")
     * @ApiRelation(type="one_to_many", target_entity="Record")
     */
    protected $records;
    /**
     * @Expose
     * @Type("array<string, string>")
     * @ApiField(bind_to="metadatas", type="array")
     */
    protected $metadata;
    /**
     * @Expose
     * @Type("ArrayCollection<PhraseanetSDK\Entity\RecordStatus>")
     * @ApiField(bind_to="status", type="relation", virtual="1")
     * @ApiRelation(type="one_to_many", target_entity="RecordStatus")
     */
    protected $status;
    /**
     * @Expose
     * @Type("ArrayCollection<PhraseanetSDK\Entity\RecordCaption>")
     * @ApiField(bind_to="caption", type="relation", virtual="1")
     * @ApiRelation(type="one_to_many", target_entity="RecordCaption")
     */
    protected $caption;

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

    /**
     * Thumbnail can be null for stories, if no representative image is set.
     */
    public function setThumbnail(Subdef $thumbnail = null)
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

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus(ArrayCollection $status)
    {
        $this->status = $status;
    }

    public function getCaption()
    {
        return $this->caption;
    }

    public function setCaption(ArrayCollection $caption)
    {
        $this->caption = $caption;
    }
}
