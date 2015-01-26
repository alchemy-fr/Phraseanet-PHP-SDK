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

use PhraseanetSDK\Annotation\ApiField as ApiField;
use PhraseanetSDK\Annotation\Id as Id;
use JMS\Serializer\Annotation\Expose as Expose;
use JMS\Serializer\Annotation\VirtualProperty as VirtualProperty;
use JMS\Serializer\Annotation\SerializedName as SerializedName;
use JMS\Serializer\Annotation\Type as Type;
use JMS\Serializer\Annotation\ExclusionPolicy as ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class Permalink
{
    /**
     * @Expose
     * @Id
     * @Type("integer")
     * @ApiField(bind_to="id", type="int")
     */
    protected $id;
    /**
     * @Expose
     * @Type("boolean")
     * @ApiField(bind_to="is_activated", type="boolean")
     */
    protected $isActivated;
    /**
     * @Expose
     * @Type("string")
     * @ApiField(bind_to="label", type="string")
     */
    protected $label;
    /**
     * @Expose
     * @Type("DateTime<'Y-m-d H:i:s'>")
     * @ApiField(bind_to="updated_on", type="date")
     */
    protected $updatedOn;
    /**
     * @Expose
     * @Type("DateTime<'Y-m-d H:i:s'>")
     * @ApiField(bind_to="created_on", type="date")
     */
    protected $createdOn;
    /**
     * @Expose
     * @Type("string")
     * @ApiField(bind_to="page_url", type="string")
     */
    protected $pageUrl;
    /**
     * @Expose
     * @Type("string")
     * @ApiField(bind_to="url", type="string")
     */
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
