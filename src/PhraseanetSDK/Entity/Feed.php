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
use PhraseanetSDK\Repository\Entry as EntryRepository;
use stdClass;

class Feed
{
    /**
     * @param EntityManager $entityManager
     * @param stdClass[] $values
     * @return Feed[]
     */
    public static function fromList(EntityManager $entityManager, array $values): array
    {
        $feeds = array();

        foreach ($values as $value) {
            $feeds[$value->id] = self::fromValue($entityManager, $value);
        }

        return $feeds;
    }

    /**
     * @param EntityManager $entityManager
     * @param stdClass $value
     * @return Feed
     */
    public static function fromValue(EntityManager $entityManager, stdClass $value): Feed
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
     * @var DateTime|null
     */
    protected $createdOn;

    /**
     * @var DateTime|null
     */
    protected $updatedOn;

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
     * The feed id
     *
     * @return integer
     */
    public function getId(): int
    {
        return $this->source->id;
    }

    /**
     * The feed title
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->source->title;
    }

    /**
     * The feed icon
     *
     * @return string
     */
    public function getIcon(): string
    {
        return $this->source->icon;
    }

    /**
     * The feed subtitle
     *
     * @return string
     */
    public function getSubTitle(): string
    {
        return $this->source->subtitle;
    }

    /**
     * Get the total entries of the feed
     *
     * @return integer
     */
    public function getTotalEntries(): int
    {
        return $this->source->total_entries;
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
     * Tell whether the feed is public or not
     *
     * @return Boolean
     */
    public function isPublic(): bool
    {
        return $this->source->public;
    }

    /**
     * Tell whether the feed is a read only feed
     *
     * @return Boolean
     */
    public function isReadonly(): bool
    {
        return $this->source->readonly;
    }

    /**
     * Tell whether the feed is deletable
     *
     * @return Boolean
     */
    public function isDeletable(): bool
    {
        return $this->source->deletable;
    }

    /**
     * @param int $offset
     * @param int $perPage
     * @return FeedEntry[]|ArrayCollection
     */
    public function getEntries($offset = 0, $perPage = 0)
    {
        /** @var EntryRepository $repo */
        $repo = $this->entityManager->getRepository('entry');
        return $$repo->findByFeed($this->getId(), $offset, $perPage);
    }
}
