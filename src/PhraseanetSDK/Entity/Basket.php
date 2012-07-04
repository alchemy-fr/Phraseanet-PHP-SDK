<?php

namespace PhraseanetSDK\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class Basket extends AbstractEntity implements EntityInterface
{
    protected $basketId;
    protected $name;
    protected $description;
    protected $pusherUsrId;
    protected $sselId;
    protected $unread;
    protected $createdOn;
    protected $updatedOn;
    protected $validationBasket;
    protected $validationUsers;
    protected $expiresOn;
    protected $validationInfos;
    protected $validationConfirmed;
    protected $validationInitiator;

    /**
     * The basket id
     *
     * @return integer
     */
    public function getBasketId()
    {
        return $this->id;
    }

    public function setBasketId($id)
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
     * The id of the user who created the basket when the current basket
     * is a validation basket
     *
     * @return integer|null
     */
    public function getPusherUsrId()
    {
        return $this->pusherUsrId;
    }

    public function setPusherUsrId($pusherUsrId)
    {
        $this->pusherUsrId = $pusherUsrId;
    }

    /**
     * Tell whether the basket has been read or not
     *
     * @return boolean
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
     * @return boolean
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
     * Get some informations about the validation, if the basket is a validation
     * basket
     *
     * @return string|null
     */
    public function getValidationInfos()
    {
        return $this->validationInfos;
    }

    public function setValidationInfos($validationInfos)
    {
        $this->validationInfos = $validationInfos;
    }

    /**
     * Tell whether the validation is confirmed
     *
     * @return boolean|null
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
     * @return boolean|null
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
