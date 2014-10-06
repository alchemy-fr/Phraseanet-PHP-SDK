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

class Quarantine
{
    /**
     *
     * @ApiField(bind_to="id", type="int")
     */
    protected $id;
    /**
     * @ApiField(bind_to="quarantine_session", type="relation")
     * @ApiRelation(type="one_to_one", target_entity="QuarantineSession")
     */
    protected $session;
    /**
     * @ApiField(bind_to="base_id", type="int")
     */
    protected $baseId;
    /**
     * @ApiField(bind_to="original_name", type="string")
     */
    protected $originalName;
    /**
     * @ApiField(bind_to="sha256", type="string")
     */
    protected $sha256;
    /**
     * @ApiField(bind_to="uuid", type="string")
     */
    protected $uuid;
    /**
     * @ApiField(bind_to="forced", type="boolean")
     */
    protected $forced;
    /**
     * @ApiField(bind_to="checks", type="array")
     */
    protected $checks;
    /**
     * @ApiField(bind_to="id", type="date")
     */
    protected $createdOn;
    /**
     * @ApiField(bind_to="id", type="date")
     */
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
    public function getSession()
    {
        return $this->session;
    }

    public function setSession($session)
    {
        $this->session = $session;
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

    public function setCreatedOn(\DateTime $createdOn = null)
    {
        $this->createdOn = $createdOn;
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

    public function setUpdatedOn(\DateTime $updatedOn = null)
    {
        $this->updatedOn = $updatedOn;
    }
}
