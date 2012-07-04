<?php

namespace PhraseanetSDK\Entity;

class QuarantineSession extends AbstractEntity implements EntityInterface
{
    protected $id;
    protected $usrId;

    /**
     * The session id
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
     * The user id
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
}
