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
        return isset($this->source->id) ? $this->source->id : 0;
    }

    /**
     * Tell whether the permalink is activated
     *
     * @return Boolean
     */
    public function isActivated()
    {
        return !empty($this->getUrl()) ? true : false;
    }

    /**
     * get the permalink label
     *
     * @return string
     */
    public function getLabel()
    {
        return isset($this->source->label) ? $this->source->label : '';
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
        return isset($this->source->url) ? $this->source->url : '' ;
    }
}
