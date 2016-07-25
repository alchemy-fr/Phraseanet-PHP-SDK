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

class Quarantine
{

    public static function fromList(array $values)
    {
        $quarantines = array();

        foreach ($values as $value) {
            $quarantines[$value->id] = self::fromValue($value);
        }

        return $quarantines;
    }

    public static function fromValue(\stdClass $value)
    {
        return new self($value);
    }

    /**
     * @var \stdClass
     */
    protected $source;

    /**
     * @var QuarantineSession
     */
    protected $session;

    /**
     * @var string[]|ArrayCollection
     */
    protected $checks;

    /**
     * @var \DateTime
     */
    protected $createdOn;

    /**
     * @var \DateTime
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
     * Get Quarantine item id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->source->id;
    }

    /**
     * Get The related quarantine session
     *
     * @return QuarantineSession
     */
    public function getSession()
    {
        return $this->session ?: $this->session = QuarantineSession::fromValue($this->source->quarantine_session);
    }

    /**
     * Get the related base id
     *
     * @return integer
     */
    public function getBaseId()
    {
        return $this->source->base_id;
    }

    /**
     * Get the item original name
     *
     * @return string
     */
    public function getOriginalName()
    {
        return $this->source->original_name;
    }

    /**
     * Get the item SHA 256 HASH
     *
     * @return string
     */
    public function getSha256()
    {
        return $this->source->sha256;
    }

    /**
     * Get the item UUID
     *
     * @return string
     */
    public function getUuid()
    {
        return $this->source->uuid;
    }

    /**
     * Tell whether the item has been forced to the quarantine
     *
     * @return Boolean
     */
    public function isForced()
    {
        return $this->source->forced;
    }

    /**
     * Get the check messages as a collection of string
     *
     * @return string[]
     */
    public function getChecks()
    {
        return $this->checks ?: $this->checks = new ArrayCollection($this->source->checks);
    }

    /**
     * Creation date
     *
     * @return \DateTime
     */
    public function getCreatedOn()
    {
        return $this->createdOn ?: new \DateTime($this->source->created_on);
    }

    /**
     * Last updated date
     *
     * @return \DateTime
     */
    public function getUpdatedOn()
    {
        return $this->updatedOn ?: new \DateTime($this->source->updated_on);
    }
}
