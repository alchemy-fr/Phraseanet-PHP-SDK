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

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;
use stdClass;

class Basket
{

    /**
     * @param array $values
     * @return Basket[]
     */
    public static function fromList(array $values): array
    {
        $baskets = array();

        foreach ($values as $value) {
            $baskets[$value->basket_id] = self::fromValue($value);
        }

        return $baskets;
    }

    public static function fromValue(stdClass $value): Basket
    {
        return new self($value);
    }

    /**
     * @var stdClass
     */
    protected $source;

    /**
     * @var User|null
     */
    protected $owner;

    /**
     * @var User|null
     */
    protected $pusher;

    /**
     * @var DateTime
     */
    protected $createdOn;

    /**
     * @var DateTime
     */
    protected $updatedOn;

    /**
     * ArrayCollection|User[]
     */
    protected $validationUsers;

    /**
     * @var DateTime
     */
    protected $expiresOn;

    /**
     * @var User|null
     */
    protected $validationInitiatorUser;

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
     * The basket id
     *
     * @return integer
     */
    public function getId(): int
    {
        return $this->source->basket_id;
    }

    /**
     * The basket name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->source->name;
    }

    /**
     * The basket description
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->source->description;
    }

    /**
     * The user who created the basket when the current basket
     * is a validation basket
     *
     * @return User|null
     */
    public function getPusher(): ?User
    {
        return $this->pusher ?: ($this->pusher = User::fromValue($this->source->pusher));
    }

    /**
     * Tell whether the basket has been read or not
     *
     * @return Boolean
     */
    public function isUnread(): bool
    {
        return $this->source->unread;
    }

    /**
     * Creation date
     *
     * @return DateTime
     * @throws Exception
     */
    public function getCreatedOn(): DateTime
    {
        return $this->createdOn ?: $this->createdOn = new DateTime($this->source->created_on);
    }

    /**
     * Last update date
     *
     * @return DateTime
     * @throws Exception
     */
    public function getUpdatedOn(): DateTime
    {
        return $this->updatedOn ?: $this->updatedOn = new DateTime($this->source->updated_on);
    }

    /**
     * Tell whether the basket is a validation basket
     *
     * @return Boolean
     */
    public function isValidationBasket(): bool
    {
        return $this->source->validation_basket;
    }

    /**
     * Return a collection of PhraseanetSDK\entity\BasketValidationParticipant object
     * if the basket is a validation basket otherwise it returns null
     *
     * @return ArrayCollection|BasketValidationParticipant[]|null
     */
    public function getValidationUsers(): ?ArrayCollection
    {
        if (! $this->isValidationBasket()) {
            return null;
        }

        return $this->validationUsers ?: $this->validationUsers = new ArrayCollection(
            BasketValidationParticipant::fromList($this->source->validation_users)
        );
    }

    /**
     * The expiration validation date, if the basket is a validation basket
     *
     * @return DateTime|null
     * @throws Exception
     */
    public function getExpiresOn(): DateTime
    {
        return $this->expiresOn ?: $this->expiresOn = new DateTime($this->source->expires_on);
    }

    /**
     * Get some information about the validation, if the basket is a validation
     * basket
     *
     * @return string|null
     */
    public function getValidationInfo(): ?string
    {
        return $this->source->validation_infos;
    }

    /**
     * Tell whether the validation is confirmed
     *
     * @return bool
     */
    public function isValidationConfirmed(): bool
    {
        return (bool) $this->source->validation_confirmed;
    }

    /**
     * Tell whether the current authenticated user initiates the validation process
     *
     * @return bool
     */
    public function isValidationInitiator(): bool
    {
        return (bool) $this->source->validation_initiator;
    }

    /**
     * @return User|null
     */
    public function getValidationInitiatorUser(): ?User
    {
      return $this->validationInitiatorUser ?: ($this->validationInitiatorUser = User::fromValue($this->source->validation_initiator_user));
    }

    /**
     * @return User|null
     */
    public function getOwner(): ?User
    {
        return $this->owner ?: ($this->owner = User::fromValue($this->source->owner));
    }
}
