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
use PhraseanetSDK\EntityManager;

class Feed
{

    public static function fromList(EntityManager $entityManager, array $values)
    {
        $feeds = array();

        foreach ($values as $value) {
            $feeds[$value->id] = self::fromValue($entityManager, $value);
        }

        return $feeds;
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
     * @var \DateTime|null
     */
    protected $createdOn;

    /**
     * @var \DateTime|null
     */
    protected $updatedOn;

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
     * The feed id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->source->id;
    }

    /**
     * The feed title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->source->title;
    }

    /**
     * The feed icon
     *
     * @return string
     */
    public function getIcon()
    {
        return $this->source->icon;
    }

    /**
     * The feed subtitle
     *
     * @return string
     */
    public function getSubTitle()
    {
        return $this->source->subtitle;
    }

    /**
     * Get the total entries of the feed
     *
     * @return integer
     */
    public function getTotalEntries()
    {
        return $this->source->total_entries;
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
     * Last updated date
     *
     * @return \DateTime
     */
    public function getUpdatedOn()
    {
        return $this->updatedOn ?: $this->updatedOn = new \DateTime($this->source->updated_on);
    }

    /**
     * Tell whether the feed is public or not
     *
     * @return Boolean
     */
    public function isPublic()
    {
        return $this->source->public;
    }

    /**
     * Tell whether the feed is a read only feed
     *
     * @return Boolean
     */
    public function isReadonly()
    {
        return $this->source->readonly;
    }

    /**
     * Tell whether the feed is deletable
     *
     * @return Boolean
     */
    public function isDeletable()
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
        return $this->entityManager
            ->getRepository('entry')
            ->findByFeed($this->getId(), $offset, $perPage);
    }
}
