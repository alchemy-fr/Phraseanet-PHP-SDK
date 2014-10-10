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

class FeedEntry
{
    /**
     *
     * @ApiField(bind_to="id", type="int")
     */
    protected $id;
    /**
     * @ApiField(bind_to="feed_id", type="int")
     */
    protected $feedId;
    /**
     * @ApiField(bind_to="feed_title", type="string")
     */
    protected $feedTitle;
    /**
     * @ApiField(bind_to="feed_url", type="string")
     */
    protected $feedUrl;
    /**
     * @ApiField(bind_to="url", type="string")
     */
    protected $url;
    /**
     * @ApiField(bind_to="author_email", type="string")
     */
    protected $authorEmail;
    /**
     * @ApiField(bind_to="author_name", type="string")
     */
    protected $authorName;
    /**
     * @ApiField(bind_to="title", type="string")
     */
    protected $title;
    /**
     * @ApiField(bind_to="subtitle", type="string")
     */
    protected $subtitle;
    /**
     * @ApiField(bind_to="created_on", type="date")
     */
    protected $createdOn;
    /**
     * @ApiField(bind_to="updated_on", type="date")
     */
    protected $updatedOn;
    /**
     * @ApiField(bind_to="items", type="relation")
     * @ApiRelation(type="one_to_many", target_entity="FeedEntryItem")
     */
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

    /**
     * @return mixed
     */
    public function getFeedTitle()
    {
        return $this->feedTitle;
    }

    /**
     * @param mixed $feedTitle
     */
    public function setFeedTitle($feedTitle)
    {
        $this->feedTitle = $feedTitle;
    }

    /**
     * @return mixed
     */
    public function getFeedUrl()
    {
        return $this->feedUrl;
    }

    /**
     * @param mixed $feedUrl
     */
    public function setFeedUrl($feedUrl)
    {
        $this->feedUrl = $feedUrl;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }
}
