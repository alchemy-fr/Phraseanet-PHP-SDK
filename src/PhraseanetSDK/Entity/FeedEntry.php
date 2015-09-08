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

class FeedEntry
{

    /**
     * @param \stdClass[] $values
     * @return FeedEntry[]
     */
    public static function fromList(array $values)
    {
        $entries = array();

        foreach ($values as $value) {
            $entries[] = self::fromValue($value);
        }

        return $entries;
    }

    /**
     * @param \stdClass $value
     * @return FeedEntry
     */
    public static function fromValue(\stdClass $value)
    {
        return new self($value);
    }

    /**
     * @var \stdClass
     */
    protected $source;

    /**
     * @var \DateTime
     */
    protected $createdOn;

    /**
     * @var \DateTime
     */
    protected $updatedOn;

    /**
     * FeedEntryItem[]|ArrayCollection
     */
    protected $items;

    /**
     * @param \stdClass $source
     */
    public function __construct(\stdClass $source)
    {
        $this->source = $source;
    }

    /**
     * The Entry id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->source->id;
    }

    /**
     * Get the author's mail of the feed entry
     *
     * @return string
     */
    public function getAuthorEmail()
    {
        return $this->source->author_email;
    }

    /**
     * Get the author's name of the feed entry
     *
     * @return string
     */
    public function getAuthorName()
    {
        return $this->source->author_name;
    }

    /**
     * Get the title of the feed entry
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->source->title;
    }

    /**
     * Get The description of the feed entry
     *
     * @return string
     */
    public function getSubtitle()
    {
        return $this->source->subtitle;
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
     * Last update date
     *
     * @return \DateTime
     */
    public function getUpdatedOn()
    {
        return $this->updatedOn ?: $this->updatedOn = new \DateTime($this->source->updated_on);
    }

    /**
     * Get the items associated to the feed entry as a collection of
     * PhraseanetSDK\Entity\FeedEntryItem object
     *
     * @return ArrayCollection|FeedEntryItem[]
     */
    public function getItems()
    {
        if (! isset($this->source->items)) {
            $this->items = new ArrayCollection();
        }

        return $this->items ?: $this->items = new ArrayCollection(FeedEntryItem::fromList($this->source->items));
    }

    /**
     * Returns the entry's feed id
     *
     * @return integer
     */
    public function getFeedId()
    {
        return $this->source->feed_id;
    }

    /**
     * @return string
     */
    public function getFeedTitle()
    {
        return $this->source->feed_title;
    }

    /**
     * @return string
     */
    public function getFeedUrl()
    {
        return $this->source->feed_url;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->source->url;
    }
}
