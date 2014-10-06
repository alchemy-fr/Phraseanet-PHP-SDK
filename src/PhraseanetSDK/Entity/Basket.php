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

class Basket
{
    /**
     * @ApiField(bind_to="ssel_id", type="int")
     */
    protected $id;
    /**
     * @ApiField(bind_to="name", type="string")
     */
    protected $name;
    /**
     * @ApiField(bind_to="description", type="string")
     */
    protected $description;
    /**
     * @ApiField(bind_to="owner", type="relation")
     * @ApiRelation(type="one_to_one", target_entity="User")
     */
    protected $owner;
    /**
     * @ApiField(bind_to="pusher", type="relation")
     * @ApiRelation(type="one_to_one", target_entity="User")
     */
    protected $pusher;
    /**
     * @ApiField(bind_to="unread", type="boolean")
     */
    protected $unread;
    /**
     * @ApiField(bind_to="created_on", type="date")
     */
    protected $createdOn;
    /**
     * @ApiField(bind_to="updated_on", type="date")
     */
    protected $updatedOn;
    /**
     * @ApiField(bind_to="validation_basket", type="boolean")
     */
    protected $validationBasket;
    /**
     * @ApiField(bind_to="validation_users", type="relation")
     * @ApiRelation(type="one_to_many", target_entity="User")
     */
    protected $validationUsers;
    /**
     * @ApiField(bind_to="validation_end_date", type="date")
     */
    protected $expiresOn;
    /**
     * @ApiField(bind_to="validation_infos", type="string")
     */
    protected $validationInfo;
    /**
     * @ApiField(bind_to="validation_confirmed", type="boolean")
     */
    protected $validationConfirmed;
    /**
     * @ApiField(bind_to="validation_initiator_user", type="relation")
     * @ApiRelation(type="one_to_one", target_entity="User")
     */
    protected $validationInitiator;

    /**
     * The basket id
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
     * The basket name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * The basket description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * The user who created the basket when the current basket
     * is a validation basket
     *
     * @return integer|null
     */
    public function getPusher()
    {
        return $this->pusher;
    }

    public function setPusher($pusher)
    {
        $this->pusher = $pusher;
    }

    /**
     * Tell whether the basket has been read or not
     *
     * @return Boolean
     */
    public function isUnread()
    {
        return $this->unread;
    }

    public function setUnread($unread)
    {
        $this->unread = $unread;
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
     * Tell whether the basket is a validation basket
     *
     * @return Boolean
     */
    public function isValidationBasket()
    {
        return $this->validationBasket;
    }

    public function setValidationBasket($validationBasket)
    {
        $this->validationBasket = $validationBasket;
    }

    /**
     * Return a collection of PhraseanetSDK\entity\BasketValidationParticipant object
     * if the basket is a validation basket otherwise it returns null
     *
     * @return ArrayCollection|null
     */
    public function getValidationUsers()
    {
        return $this->validationUsers;
    }

    public function setValidationUsers(ArrayCollection $validationUsers)
    {
        $this->validationUsers = $validationUsers;
    }

    /**
     * The expiration validation date, if the basket is a validation basket
     *
     * @return \DateTime|null
     */
    public function getExpiresOn()
    {
        return $this->expiresOn;
    }

    public function setExpiresOn(\DateTime $expiresOn)
    {
        $this->expiresOn = $expiresOn;
    }

    /**
     * Get some information about the validation, if the basket is a validation
     * basket
     *
     * @return string|null
     */
    public function getValidationInfo()
    {
        return $this->validationInfo;
    }

    public function setValidationInfo($validationInfo)
    {
        $this->validationInfo = $validationInfo;
    }

    /**
     * Tell whether the validation is confirmed
     *
     * @return Boolean|null
     */
    public function isValidationConfirmed()
    {
        return $this->validationConfirmed;
    }

    public function setValidationConfirmed($validationConfirmed)
    {
        $this->validationConfirmed = $validationConfirmed;
    }

    /**
     * Tell whether the current authenticated user initiates the validation process
     *
     * @return Boolean|null
     */
    public function isValidationInitiator()
    {
        return $this->validationInitiator;
    }

    public function setValidationInitiator($validationInitiator)
    {
        $this->validationInitiator = $validationInitiator;
    }
}
