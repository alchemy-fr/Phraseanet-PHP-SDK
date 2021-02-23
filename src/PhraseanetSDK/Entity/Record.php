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
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;
use stdClass;

class Record
{
    /**
     * @param stdClass[] $values
     * @return Record[]
     */
    public static function fromList(array $values): array
    {
        $records = array();

        foreach ($values as $value) {
            $records[] = self::fromValue($value);
        }

        return $records;
    }

    /**
     * @param stdClass $value
     * @return Record
     */
    public static function fromValue(stdClass $value): Record
    {
        return new self($value);
    }

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
     * @var Subdef
     */
    protected $thumbnail;

    /**
     * @var Technical[]
     */
    protected $technicalInformation;

    /**
     * @var Metadata[]
     */
    protected $metadata;

    /**
     * @var Subdef[]
     */
    protected $subdefs;

    /**
     * @var RecordStatus[]
     */
    protected $status;

    /**
     * @var RecordCaption[]
     */
    protected $caption;

    /**
     * @param stdClass $source
     */
    public function __construct(stdClass $source)
    {
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
        return $this->getDataboxId() . '_' . $this->getRecordId();
    }

    /**
     * Get the record id
     *
     * @return integer
     */
    public function getRecordId(): int
    {
        return $this->source->record_id;
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
     * Get the base id.
     *
     * @return integer
     */
    public function getBaseId(): int
    {
        return $this->source->base_id;
    }

    /**
     * Get the record title
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->source->title;
    }

    /**
     * Get the record mime type
     *
     * @return string
     */
    public function getMimeType(): string
    {
        return $this->source->mime_type;
    }

    /**
     * Get the record original name
     *
     * @return string
     */
    public function getOriginalName(): string
    {
        return $this->source->original_name;
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
     * Get the record SHA256 hash
     *
     * @return string
     */
    public function getSha256(): string
    {
        return $this->source->sha256;
    }

    /**
     * Return the thumbnail of the record as a PhraseanetSDK\Entity\Subdef object
     * if the thumbnail exists null otherwise
     *
     * @return Subdef|null
     */
    public function getThumbnail(): ?Subdef
    {
        if (! isset($this->source->thumbnail)) {
            return null;
        }

        return $this->thumbnail ?: $this->thumbnail = Subdef::fromValue($this->source->thumbnail);
    }

    /**
     * Get the Record phraseaType IMAGE|VIDEO|DOCUMENT etc..
     *
     * @return string
     */
    public function getPhraseaType(): string
    {
        return $this->source->phrasea_type;
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
     * Get a collection of Phraseanet\Entity\Technical data objects
     *
     * @return ArrayCollection|Technical[]
     */
    public function getTechnicalInformation()
    {
        if (! isset($this->source->technical_informations)) {
            $this->technicalInformation = new ArrayCollection();
        }

        return $this->technicalInformation ?: new ArrayCollection(Technical::fromList(
            $this->source->technical_informations
        ));
    }

    /**
     * Return a collection of PhraseanetSDK\Entity\Subdef for the record
     *
     * @return ArrayCollection|Subdef[]
     */
    public function getSubdefs()
    {
        if (! isset($this->source->subdefs)) {
            $this->subdefs = new ArrayCollection();
        }

        return $this->subdefs ?: new ArrayCollection(Subdef::fromList($this->source->subdefs));
    }

    /**
     * @return RecordStatus[]|ArrayCollection
     */
    public function getStatus()
    {
        if (! isset($this->source->status)) {
            $this->status = new ArrayCollection();
        }

        return $this->status ?: new ArrayCollection(RecordStatus::fromList($this->source->status));
    }

    /**
     * @return RecordCaption[]|ArrayCollection
     */
    public function getCaption()
    {
        if (! isset($this->source->caption)) {
            $this->caption = new ArrayCollection();
        }

        return $this->caption ?: new ArrayCollection(RecordCaption::fromList($this->source->caption));
    }

    /**
     * @return Metadata[]|ArrayCollection
     */
    public function getMetadata()
    {
        if (! isset($this->source->metadata)) {
            $this->metadata = new ArrayCollection();
        }

        return $this->metadata ?: new ArrayCollection(Metadata::fromList($this->source->metadata));
    }
}
