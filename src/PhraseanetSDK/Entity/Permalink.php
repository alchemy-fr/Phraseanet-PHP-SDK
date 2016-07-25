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

class Permalink
{

    public static function fromValue(\stdClass $value)
    {
        return new self($value);
    }

    /**
     * @var \stdClass
     */
    protected $source;

    /**
     * @var \DateTimeInterface
     */
    protected $createdOn;

    /**
     * @var \DateTimeInterface
     */
    protected $updatedOn;

    /**
     * @param \stdClass $source
     */
    public function __construct(\stdClass $source)
    {
        $this->source = $source;
    }

    /**
     * @return \stdClass
     */
    public function getRawData()
    {
        return $this->source;
    }

    /**
     * Get the permalink id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->source->id;
    }

    /**
     * Tell whether the permalink is activated
     *
     * @return Boolean
     */
    public function isActivated()
    {
        return $this->source->is_activated;
    }

    /**
     * get the permalink label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->source->label;
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
     * Creation date
     *
     * @return \DateTime
     */
    public function getCreatedOn()
    {
        return $this->createdOn ?: $this->createdOn = new \DateTime($this->source->created_on);
    }

    /**
     * Get the page url
     *
     * @return string
     */
    public function getPageUrl()
    {
        return $this->source->page_url;
    }

    /**
     * Get Url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->source->url;
    }
}
