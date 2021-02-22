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

use stdClass;

class BasketValidationParticipant
{
    /**
     * @param stdClass[] $values
     * @return BasketValidationParticipant[]
     */
    public static function fromList(array $values): array
    {
        $participants = array();

        foreach ($values as $value) {
            $participants[] = self::fromValue($value);
        }

        return $participants;
    }

    /**
     * @param stdClass|null $value
     * @return BasketValidationParticipant|null
     */
    public static function fromValue(?stdClass $value): ?BasketValidationParticipant
    {
        return $value ? new self($value) : null;
    }

    /**
     * @var stdClass
     */
    protected $source;

    /**
     * @var User
     */
    protected $user;

    /**
     * @param stdClass $source
     */
    public function __construct(stdClass $source)
    {
        $this->source = $source;
    }

    /**
     * @return stdClass
     */
    public function getRawData(): stdClass
    {
        return $this->source;
    }

    /**
     * Get the user
     *
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user ?: $this->user = User::fromValue($this->source->user);
    }

    /**
     * Tell whether the participant is confirmed
     *
     * @return bool
     */
    public function isConfirmed(): bool
    {
        return $this->source->confirmed;
    }

    /**
     * Tell whether the participant can agree
     *
     * @return bool
     */
    public function canAgree(): bool
    {
        return $this->source->can_agree;
    }

    /**
     * Tell whether the participant can see the other participants
     *
     * @return bool
     */
    public function canSeeOthers(): bool
    {
        return $this->source->can_see_others;
    }

    /**
     * Tell whether the participant can access data in readonly mode
     *
     * @return bool
     */
    public function isReadOnly(): bool
    {
        return $this->source->readonly;
    }
}
