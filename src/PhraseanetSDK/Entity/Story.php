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
use PhraseanetSDK\EntityManager;

class Story
{

    public static function fromList(EntityManager $entityManager, array $values)
    {
        $stories = array();

        foreach ($values as $value) {
            $stories[] = self::fromValue($entityManager, $value);
        }

        return $stories;
    }

    public static function fromValue(EntityManager $entityManager, \stdClass $value)
    {
        return new self($entityManager, $value);
    }

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var \stdClass
     */
    protected $source;

    /**
     * @var \DateTime
     */
    protected $updatedOn;

    /**
     * @var \DateTime
     */
    protected $createdOn;

    /**
     * @var Subdef|null
     */
    protected $thumbnail;

    /**
     * @var ArrayCollection|Record[]
     */
    protected $records;

    /**
     * @var ArrayCollection|Metadata[]
     */
    protected $metadata;

    /**
     * @var ArrayCollection|RecordStatus[]
     */
    protected $status;

    /**
     * @var ArrayCollection|RecordCaption[]
     */
    protected $caption;

    /**
     * @var int
     */
    protected $recordCount;

    /**
     * @param EntityManager $entityManager
     * @param \stdClass $source
     */
    public function __construct(EntityManager $entityManager, \stdClass $source)
    {
        $this->entityManager = $entityManager;
        $this->source = $source;
    }

    /**
     * @return \stdClass
     */
    public function getRawData()
    {
        return $this->source;
    }

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
        return isset($this->source->story_id) ? $this->source->story_id : $this->source->record_id;
    }

    /**
     * Get the databox id
     *
     * @return integer
     */
    public function getDataboxId()
    {
        return $this->source->databox_id;
    }

    /**
     * @return null|Subdef
     */
    public function getThumbnail()
    {
        if (isset($this->source->subdefs->thumbnail)) {
            $thumbnail = $this->source->subdefs->thumbnail;
            $thumbnail->name = 'thumbnail';
        } elseif (isset($this->source->thumbnail)) {
            $thumbnail = $this->source->thumbnail;
        } else {
            return null;
        }

        return $this->thumbnail ?: $this->thumbnail = Subdef::fromValue($thumbnail);
    }

    /**
     * Last updated date
     *
     * @return \DateTime
     */
    public function getUpdatedOn()
    {
        return $this->updatedOn ?: $this->updatedOn = new \DateTime($this->source->updated_on);
    }

    /**
     * Creation date
     *
     * @return \DateTime
     */
    public function getCreatedOn()
    {
        return $this->createdOn ?: $this->createdOn = new \DateTime($this->source->created_on);
    }

    /**
     * Get the record collection id
     *
     * @return integer
     */
    public function getCollectionId()
    {
        return $this->source->collection_id;
    }

    /**
     * Get the record UUID
     *
     * @return string
     */
    public function getUuid()
    {
        return $this->source->uuid;
    }

    /**
     * @return int
     */
    public function getRecordCount()
    {
        return $this->recordCount !== null ?
            $this->recordCount :
            $this->recordCount =
                (isset($this->source->record_count) ? $this->source->record_count : count($this->getRecords()));
    }

    /**
     * @return Record[]|ArrayCollection
     */
    public function getRecords()
    {
        if (! isset($this->source->records)) {
            $this->records = new ArrayCollection();
        }

        return $this->records ?: $this->records = new ArrayCollection(
            Record::fromList((array) $this->source->records)
        );
    }

    /**
     * @return Metadata[]|ArrayCollection
     */
    public function getMetadata()
    {
        if (! isset($this->source->metadata)) {
            // fallback on caption source
            $this->metadata = $this->getCaption();
        }

        return $this->metadata ?: $this->metadata = new ArrayCollection(
            Metadata::fromList((array) $this->source->metadata)
        );
    }

    /**
     * @return RecordStatus[]|ArrayCollection
     */
    public function getStatus()
    {
        if (! isset($this->status)) {
            $this->status = $this->entityManager->getRepository('recordStatus')->findByRecord(
                $this->getDataboxId(),
                $this->getStoryId()
            );
        }

        return $this->status;
    }

    /**
     * @return RecordCaption[]|ArrayCollection
     */
    public function getCaption()
    {
        if (! isset($this->caption) && isset($this->source->caption)) {
            $caption = $this->source->caption;
            if (is_object($this->source->caption)) {
                $caption = get_object_vars($this->source->caption);
            }

            $this->caption = RecordCaption::fromList($caption);
        }

        if (! isset($this->caption)) {
            $this->caption = $this->entityManager->getRepository('caption')->findByRecord(
                $this->getDataboxId(),
                $this->getStoryId()
            );
        }

        return $this->caption;
    }
}
