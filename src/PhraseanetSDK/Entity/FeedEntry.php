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

class FeedEntry extends AbstractEntity
{
    protected $id;
    protected $feedId;
    protected $authorEmail;
    protected $authorName;
    protected $title;
    protected $subtitle;
    protected $createdOn;
    protected $updatedOn;
    protected $items;

    /**
     * The Entry id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get the author's mail of the feed entry
     *
     * @return string
     */
    public function getAuthorEmail()
    {
        return $this->authorEmail;
    }

    public function setAuthorEmail($authorEmail)
    {
        $this->authorEmail = $authorEmail;
    }

    /**
     * Get the author's name of the feed entry
     *
     * @return string
     */
    public function getAuthorName()
    {
        return $this->authorName;
    }

    public function setAuthorName($authorName)
    {
        $this->authorName = $authorName;
    }

    /**
     * Get the title of the feed entry
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get The description of the feed entry
     *
     * @return string
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;
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
     * Last update date
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
     * Get the items associated to the feed entry as a collection of
     * PhraseanetSDK\Entity\FeedEntryItem object
     *
     * @return ArrayCollection
     */
    public function getItems()
    {
        return $this->items;
    }

    public function setItems(ArrayCollection $items)
    {
        $this->items = $items;
    }

    /**
     * Recover the entrie's feed id
     * @return integer
     */
    public function getFeedId()
    {
        return $this->feedId;
    }

    public function setFeedId($feedId)
    {
        $this->feedId = $feedId;
    }
}
