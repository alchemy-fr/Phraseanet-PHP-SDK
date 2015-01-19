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

use PhraseanetSDK\Annotation\ApiField as ApiField;
use PhraseanetSDK\Annotation\ApiRelation as ApiRelation;

class BasketValidationParticipant
{
    /**
     * @ApiField(bind_to="user", type="relation")
     * @ApiRelation(type="one_to_one", target_entity="User")
     */
    protected $user;
    /**
     * @ApiField(bind_to="confirmed", type="boolean")
     */
    protected $confirmed;
    /**
     * @ApiField(bind_to="can_agree", type="boolean")
     */
    protected $canAgree;
    /**
     * @ApiField(bind_to="can_see_others", type="boolean")
     */
    protected $canSeeOthers;
    /**
     * @ApiField(bind_to="readonly", type="boolean")
     */
    protected $readOnly;

    /**
     * Get the user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
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
     * Tell whether the participant can agree
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
     * Tell whether the participant can see the other participants
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

    /**
     * Tell whether the participant can access data in readonly mode
     *
     * @return Boolean
     */
    public function isReadOnly()
    {
        return $this->readOnly;
    }

    public function setReadonly($readonly)
    {
        $this->readOnly = $readonly;
    }
}
