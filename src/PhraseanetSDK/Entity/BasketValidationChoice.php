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

class BasketValidationChoice
{
    /**
     * @ApiField(bind_to="agreement", type="boolean", nullable="1")
     */
    protected $agreement;
    /**
     * @ApiField(bind_to="updated_on", type="date")
     */
    protected $updatedOn;
    /**
     * @ApiField(bind_to="note", type="int")
     */
    protected $note;
    /**
     * @ApiField(bind_to="validation_user", type="relation")
     * @ApiRelation(type="one_to_one", target_entity="BasketValidationParticipant")
     */
    protected $participant;

    /**
     * Get the validation user
     *
     * @return BasketValidationParticipant
     */
    public function getParticipant()
    {
        return $this->participant;
    }

    public function setParticipant(BasketValidationParticipant $participant)
    {
        $this->participant = $participant;
    }

    /**
     * Get last update date
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
     * Get the annotation about the validation of the current authenticated user
     *
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    public function setNote($note)
    {
        $this->note = $note;
    }

    /**
     * Get the agreement of the current authenticated user
     *
     * - null : no response yet
     * - true : accepted
     * - false: rejected
     *
     * @return null|boolean
     */
    public function getAgreement()
    {
        return $this->agreement;
    }

    public function setAgreement($agreement)
    {
        $this->agreement = $agreement;
    }
}
