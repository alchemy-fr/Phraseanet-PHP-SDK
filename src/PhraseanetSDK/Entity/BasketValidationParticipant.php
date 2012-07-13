<?php

namespace PhraseanetSDK\Entity;

class BasketValidationParticipant extends AbstractEntity implements EntityInterface
{
    protected $usrId;
    protected $usrName;
    protected $confirmed;
    protected $canAgree;
    protected $canSeeOthers;

    /**
     * Get the user id
     *
     * @return integer
     */
    public function getUsrId()
    {
        return $this->usrId;
    }

    public function setUsrId($usrId)
    {
        $this->usrId = $usrId;
    }

    /**
     * Get the user name
     *
     * @return string
     */
    public function getUsrName()
    {
        return $this->usrName;
    }

    public function setUsrName($usrName)
    {
        $this->usrName = $usrName;
    }

    /**
     * Tell whether the participant is confirmed
     *
     * @return Boolean
     */
    public function isConfirmed()
    {
        return $this->confirmed;
    }

    public function setConfirmed($confirmed)
    {
        $this->confirmed = $confirmed;
    }

    /**
     * Tell whether the particpant can agree
     *
     * @return Boolean
     */
    public function canAgree()
    {
        return $this->canAgree;
    }

    public function setCanAgree($canAgree)
    {
        $this->canAgree = $canAgree;
    }

    /**
     * Tell whether the participant can see the other particpants
     *
     * @return Boolean
     */
    public function canSeeOthers()
    {
        return $this->canSeeOthers;
    }

    public function setCanSeeOthers($canSeeOthers)
    {
        $this->canSeeOthers = $canSeeOthers;
    }

}
