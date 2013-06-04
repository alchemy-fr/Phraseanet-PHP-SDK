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

class Feed extends AbstractEntity implements EntityInterface
{
    /**
     *
     * @var int
     */
    protected $id;

    /**
     *
     * @var string
     */
    protected $title;

    /**
     *
     * @var string
     */
    protected $icon;

    /**
     *
     * @var string
     */
    protected $subTitle;

    /**
     *
     * @var int
     */
    protected $totalEntries;

    /**
     *
     * @var string
     */
    protected $createdOn;

    /**
     *
     * @var string
     */
    protected $updatedOn;

    /**
     *
     * @var Doctrine\Common\ArrayCollection
     */
    protected $entries;

    /**
     *
     * @var Boolean
     */
    protected $public;

    /**
     *
     * @var Boolean
     */
    protected $readonly;

    /**
     *
     * @var Boolean
     */
    protected $deletable;

    /**
     * The feed id
     *
     * @return type
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
     * The feed title
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
     * The feed icon
     *
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    public function setIcon($icon)
    {
        $this->icon = $icon;
    }

    /**
     * The feed subtitle
     *
     * @return string
     */
    public function getSubTitle()
    {
        return $this->subTitle;
    }

    public function setSubTitle($subTitle)
    {
        $this->subTitle = $subTitle;
    }

    /**
     * Get the total entries of the feed
     *
     * @return type
     */
    public function getTotalEntries()
    {
        return $this->totalEntries;
    }

    public function setTotalEntries($totalEntries)
    {
        $this->totalEntries = $totalEntries;
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
     * Last updted date
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
     * Get the feed entries
     * Return a collection of PraseanetSDL\Entity\FeedEntry object
     *
     * /!\ This method requests the API
     *
     * @param  integer         $offset  The offset
     * @param  interger        $perPage The number of items
     * @return ArrayCollection
     */
    public function getEntries($offset, $perPage)
    {
        return $this->em->getRepository('entry')->findByFeed($this->getId(), $offset, $perPage);
    }

    public function setEntries(ArrayCollection $entries)
    {
        $this->entries = $entries;
    }

    /**
     * Tell whether the feed is public or not
     *
     * @return Boolean
     */
    public function isPublic()
    {
        return $this->public;
    }

    public function setPublic($public)
    {
        $this->public = $public;
    }

    /**
     * Tell whether the feed is a read only feed
     *
     * @return Boolean
     */
    public function isReadonly()
    {
        return $this->readonly;
    }

    public function setReadonly($readonly)
    {
        $this->readonly = $readonly;
    }

    /**
     * Tell whether the feed is deletable
     *
     * @return Boolean
     */
    public function isDeletable()
    {
        return $this->deletable;
    }

    public function setDeletable($deletable)
    {
        $this->deletable = $deletable;
    }
}
