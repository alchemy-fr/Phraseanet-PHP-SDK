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

class BasketValidationParticipant
{

    public static function fromList(array $values)
    {
        $participants = array();

        foreach ($values as $value) {
            $participants[] = self::fromValue($value);
        }

        return $participants;
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
     * @var User
     */
    protected $user;

    /**
     * @param \stdClass $source
     */
    public function __construct(\stdClass $source)
    {
        $this->source = $source;
    }

    /**
     * Get the user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user ?: $this->user = User::fromValue($this->source->user);
    }

    /**
     * Tell whether the participant is confirmed
     *
     * @return bool
     */
    public function isConfirmed()
    {
        return $this->source->confirmed;
    }

    /**
     * Tell whether the participant can agree
     *
     * @return bool
     */
    public function canAgree()
    {
        return $this->source->can_agree;
    }

    /**
     * Tell whether the participant can see the other participants
     *
     * @return bool
     */
    public function canSeeOthers()
    {
        return $this->source->can_see_others;
    }

    /**
     * Tell whether the participant can access data in readonly mode
     *
     * @return bool
     */
    public function isReadOnly()
    {
        return $this->source->readonly;
    }
}
