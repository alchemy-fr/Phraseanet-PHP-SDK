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

class Quarantine extends AbstractEntity implements EntityInterface
{
    protected $id;
    protected $quarantineSession;
    protected $baseId;
    protected $originalName;
    protected $sha256;
    protected $uuid;
    protected $forced;
    protected $checks;
    protected $createdOn;
    protected $updatedOn;

    /**
     * Get Quarantine item id
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
     * Get The related quarantine session
     *
     * @return QuarantineSession
     */
    public function getQuarantineSession()
    {
        return $this->quarantineSession;
    }

    public function setQuarantineSession(QuarantineSession $session)
    {
        $this->quarantineSession = $session;
    }

    /**
     * Get the related base id
     *
     * @return integer
     */
    public function getBaseId()
    {
        return $this->baseId;
    }

    public function setBaseId($baseId)
    {
        $this->baseId = $baseId;
    }

    /**
     * Get the item original name
     *
     * @return string
     */
    public function getOriginalName()
    {
        return $this->originalName;
    }

    public function setOriginalName($originalName)
    {
        $this->originalName = $originalName;
    }

    /**
     * Get the item SHA 256 HASH
     *
     * @return string
     */
    public function getSha256()
    {
        return $this->sha256;
    }

    public function setSha256($sha256)
    {
        $this->sha256 = $sha256;
    }

    /**
     * Get the item UUID
     *
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    public function setUuid($uuid)
    {
        $this->uuid = $uuid;
    }

    /**
     * Tell whether the item has been forced to the quarantine
     *
     * @return Boolean
     */
    public function isForced()
    {
        return $this->forced;
    }

    public function setForced($forced)
    {
        $this->forced = $forced;
    }

    /**
     * Get the check messages as a collection of string
     *
     * @return ArrayCollection
     */
    public function getChecks()
    {
        return $this->checks;
    }

    public function setChecks(ArrayCollection $checks)
    {
        $this->checks = $checks;
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
     * Last updated date
     *
     * @return type
     */
    public function getUpdatedOn()
    {
        return $this->updatedOn;
    }

    public function setUpdatedOn(\DateTime $updatedOn)
    {
        $this->updatedOn = $updatedOn;
    }

}
