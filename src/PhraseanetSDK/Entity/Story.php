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

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;
use PhraseanetSDK\EntityManager;
use PhraseanetSDK\Exception\NotFoundException;
use PhraseanetSDK\Exception\TokenExpiredException;
use PhraseanetSDK\Exception\UnauthorizedException;
use PhraseanetSDK\Repository\Caption as CaptionRepository;
use stdClass;
use PhraseanetSDK\Repository\RecordStatus as RecordStatusRepository;

class Story
{
    /**
     * @param EntityManager $entityManager
     * @param stdClass[] $values
     * @return Story[]
     */
    public static function fromList(EntityManager $entityManager, array $values): array
    {
        $stories = array();

        foreach ($values as $value) {
            $stories[] = self::fromValue($entityManager, $value);
        }

        return $stories;
    }

    /**
     * @param EntityManager $entityManager
     * @param stdClass $value
     * @return Story
     */
    public static function fromValue(EntityManager $entityManager, stdClass $value): Story
    {
        return new self($entityManager, $value);
    }

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var stdClass
     */
    protected $source;

    /**
     * @var DateTime
     */
    protected $updatedOn;

    /**
     * @var DateTime
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
     * @param stdClass $source
     */
    public function __construct(EntityManager $entityManager, stdClass $source)
    {
        $this->entityManager = $entityManager;
        $this->source = $source;
    }

    /**
     * @return stdClass
     */
    public function getRawData(): stdClass
    {
        return $this->source;
    }

    /**
     * Get unique id
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->getDataboxId().'_'.$this->getStoryId();
    }

    /**
     * Get the record id
     *
     * @return integer
     */
    public function getStoryId(): int
    {
        return $this->source->story_id;
    }

    /**
     * Get the databox id
     *
     * @return integer
     */
    public function getDataboxId(): int
    {
        return $this->source->databox_id;
    }

    /**
     * @return null|Subdef
     */
    public function getThumbnail(): ?Subdef
    {
        if (! isset($this->source->thumbnail)) {
            return null;
        }

        return $this->thumbnail ?: $this->thumbnail = Subdef::fromValue($this->source->thumbnail);
    }

    /**
     * Last updated date
     *
     * @return DateTime
     * @throws Exception
     */
    public function getUpdatedOn(): DateTime
    {
        return $this->updatedOn ?: $this->updatedOn = new DateTime($this->source->updated_on);
    }

    /**
     * Creation date
     *
     * @return DateTime
     * @throws Exception
     */
    public function getCreatedOn(): DateTime
    {
        return $this->createdOn ?: $this->createdOn = new DateTime($this->source->created_on);
    }

    /**
     * Get the record collection id
     *
     * @return integer
     */
    public function getCollectionId(): int
    {
        return $this->source->collection_id;
    }

    /**
     * Get the record UUID
     *
     * @return string
     */
    public function getUuid(): string
    {
        return $this->source->uuid;
    }

    /**
     * @return int
     */
    public function getRecordCount(): int
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
            $this->metadata = new ArrayCollection();
        }

        return $this->metadata ?: $this->metadata = new ArrayCollection(
            Metadata::fromList((array) $this->source->metadata)
        );
    }

    /**
     * @return RecordStatus[]|ArrayCollection
     * @throws NotFoundException
     * @throws TokenExpiredException
     * @throws UnauthorizedException
     */
    public function getStatus()
    {
        if (! isset($this->status)) {
            /** @var RecordStatusRepository $repo */
            $repo = $this->entityManager->getRepository('recordStatus');
            $this->status = $repo->findByRecord(
                $this->getDataboxId(),
                $this->getStoryId()
            );
        }

        return $this->status;
    }

    /**
     * @return RecordCaption[]|ArrayCollection
     * @throws NotFoundException
     * @throws TokenExpiredException
     * @throws UnauthorizedException
     */
    public function getCaption()
    {
        if (! isset($this->caption) && isset($this->source->caption)) {
            $this->caption = RecordCaption::fromList((array) $this->source->caption);
        }

        if (! isset($this->caption)) {
            /** @var CaptionRepository $repo */
            $repo = $this->entityManager->getRepository('caption');
            $this->caption = $repo->findByRecord(
                $this->getDataboxId(),
                $this->getStoryId()
            );
        }

        return $this->caption;
    }
}
