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

class Permalink extends AbstractEntity
{
    protected $id;
    protected $isActivated;
    protected $label;
    protected $updatedOn;
    protected $createdOn;
    protected $pageUrl;
    protected $url;

    /**
     * Get the permalink id
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
     * Tell whether the permalink is activated
     *
     * @return Boolean
     */
    public function isActivated()
    {
        return $this->isActivated;
    }

    public function setIsActivated($isActivated)
    {
        $this->isActivated = $isActivated;
    }

    /**
     * get the permalink label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    public function setLabel($label)
    {
        $this->label = $label;
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
     * Get the page url
     *
     * @return string
     */
    public function getPageUrl()
    {
        return $this->pageUrl;
    }

    public function setPageUrl($pageUrl)
    {
        $this->pageUrl = $pageUrl;
    }

    /**
     * Get Url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }
}
