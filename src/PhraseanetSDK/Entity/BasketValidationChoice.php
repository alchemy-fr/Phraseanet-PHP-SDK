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

class BasketValidationChoice
{

    public static function fromList(array $values)
    {
        $choices = array();

        foreach ($values as $value) {
            $choices[] = self::fromValue($value);
        }

        return $choices;
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
     * @var \DateTime|null
     */
    protected $updatedOn;

    /**
     * @var BasketValidationParticipant
     */
    protected $participant;

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
     * Get the validation user
     *
     * @return BasketValidationParticipant
     */
    public function getParticipant()
    {
        return $this->participant ?: $this->participant = BasketValidationParticipant::fromValue(
            $this->source->validation_user
        );
    }

    /**
     * Get last update date
     *
     * @return \DateTime
     */
    public function getUpdatedOn()
    {
        return $this->updatedOn ?: $this->updatedOn = new \DateTime($this->source->updated_on);
    }

    /**
     * Get the annotation about the validation of the current authenticated user
     *
     * @return int
     */
    public function getNote()
    {
        return (int) $this->source->note;
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
        return $this->source->agreement;
    }
}
