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
use Exception;
use stdClass;

class Permalink
{
    /**
     * @param stdClass $value
     * @return Permalink
     */
    public static function fromValue(stdClass $value): Permalink
    {
        return new self($value);
    }

    /**
     * @var stdClass
     */
    protected $source;

    /**
     * @var DateTime
     */
    protected $createdOn;

    /**
     * @var DateTime
     */
    protected $updatedOn;

    /**
     * @param stdClass $source
     */
    public function __construct(stdClass $source)
    {
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
     * Get the permalink id
     *
     * @return integer
     */
    public function getId(): int
    {
        return $this->source->id;
    }

    /**
     * Tell whether the permalink is activated
     *
     * @return Boolean
     */
    public function isActivated(): bool
    {
        return $this->source->is_activated;
    }

    /**
     * get the permalink label
     *
     * @return string
     */
    public function getLabel(): string
    {
        return $this->source->label;
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
     * Get the page url
     *
     * @return string
     */
    public function getPageUrl(): string
    {
        return $this->source->page_url;
    }

    /**
     * Get Url
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->source->url;
    }
}
