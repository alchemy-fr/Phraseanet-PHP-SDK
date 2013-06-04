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

class BasketValidationChoice extends AbstractEntity implements EntityInterface
{
    protected $validationUser;
    protected $agreement;
    protected $updatedOn;
    protected $note;

    /**
     * Get the validation user
     *
     * @return BasketValidationParticipant
     */
    public function getValidationUser()
    {
        return $this->validationUser;
    }

    public function setValidationUser(BasketValidationParticipant $validationUser)
    {
        $this->validationUser = $validationUser;
    }

    /**
     * Get the user agreement
     *
     * - null : no response yet
     * - true : accepted
     * - false: rejected
     *
     * @return Boolean|null
     */
    public function getAgreement()
    {
        return $this->agreement;
    }

    public function setAgreement($agreement)
    {
        $this->agreement = $agreement;
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
     * Get the user annotation
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
}
