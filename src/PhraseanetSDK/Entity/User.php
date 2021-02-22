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
use Exception;
use stdClass;

class User
{
    /**
     * @param stdClass[] $values
     * @return User[]
     */
    public static function fromList(array $values): array
    {
        $users = array();

        foreach ($values as $value) {
            $users[$value->id] = self::fromValue($value);
        }

        return $users;
    }

    /**
     * @param stdClass|null $value
     * @return User|null
     */
    public static function fromValue(?stdClass $value): ?User
    {
        return $value ? new self($value) : null;
    }

    /**
     * @var stdClass
     */
    protected $source;

    /**
     * @ApiField(bind_to="updated_on", type="date")
     */
    protected $updatedOn;

    /**
     * @ApiField(bind_to="created_on", type="date")
     */
    protected $createdOn;

    /**
     * @ApiField(bind_to="last_connection", type="date")
     */
    protected $lastConnection;

    /**
     * @var array
     */
    protected $collectionRights = array();

    /**
     * @var bool
     */
    protected $hasCollectionRights = false;

    /**
     * @var array
     */
    protected $collectionDemands = array();

    /**
     * @var bool
     */
    protected $hasCollectionDemands = false;

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
     * @return string
     */
    public function getAddress(): string
    {
        return $this->source->address;
    }

    /**
     * @param string $address
     */
    public function setAddress(string $address)
    {
        $this->source->address = $address;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->source->city;
    }

    /**
     * @param string $city
     */
    public function setCity(string $city)
    {
        $this->source->city = $city;
    }

    /**
     * @return string
     */
    public function getCompany(): string
    {
        return $this->source->company;
    }

    /**
     * @param string $company
     */
    public function setCompany(string $company)
    {
        $this->source->company = $company;
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->source->country;
    }

    /**
     * @param string $country
     */
    public function setCountry(string $country)
    {
        $this->source->country = $country;
    }

    /**
     * @return DateTime
     * @throws Exception
     */
    public function getCreatedOn(): DateTime
    {
        return $this->createdOn ?: $this->createdOn = new DateTime($this->source->created_on);
    }

    /**
     * @param DateTime $createdOn
     */
    public function setCreatedOn(DateTime $createdOn)
    {
        $this->createdOn = $createdOn;
    }

    /**
     * @return string
     */
    public function getDisplayName(): string
    {
        return $this->source->display_name;
    }

    /**
     * @param string $displayName
     */
    public function setDisplayName(string $displayName)
    {
        $this->source->display_name = $displayName;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->source->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->source->email = $email;
    }

    /**
     * @return string
     */
    public function getFax(): string
    {
        return $this->source->fax;
    }

    /**
     * @param string $fax
     */
    public function setFax(string $fax)
    {
        $this->source->fax = $fax;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->source->first_name;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName)
    {
        $this->source->first_name = $firstName;
    }

    /**
     * @return string
     */
    public function getGender(): string
    {
        return $this->source->gender;
    }

    /**
     * @param string $gender
     */
    public function setGender(string $gender)
    {
        $this->source->gender = $gender;
    }

    /**
     * @return string
     */
    public function getGeonameId(): string
    {
        return $this->source->geoname_id;
    }

    /**
     * @param string $geonameId
     */
    public function setGeonameId(string $geonameId)
    {
        $this->source->geoname_id = $geonameId;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->source->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->source->id = $id;
    }

    /**
     * @return string
     */
    public function getJob(): string
    {
        return $this->source->job;
    }

    /**
     * @param string $job
     */
    public function setJob(string $job)
    {
        $this->source->job = $job;
    }

    /**
     * @return DateTime
     * @throws Exception
     */
    public function getLastConnection(): DateTime
    {
        return $this->lastConnection ?: $this->lastConnection = new DateTime($this->source->last_connection);
    }

    /**
     * @param DateTime $lastConnection
     */
    public function setLastConnection(DateTime $lastConnection)
    {
        $this->lastConnection = $lastConnection;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->source->last_name;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName)
    {
        $this->source->last_name = $lastName;
    }

    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->source->locale;
    }

    /**
     * @param string $locale
     */
    public function setLocale(string $locale)
    {
        $this->source->locale = $locale;
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->source->login;
    }

    /**
     * @param string $login
     */
    public function setLogin(string $login)
    {
        $this->source->login = $login;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->source->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone(string $phone)
    {
        $this->source->phone = $phone;
    }

    /**
     * @return string
     */
    public function getPosition(): string
    {
        return $this->source->position;
    }

    /**
     * @param string $position
     */
    public function setPosition(string $position)
    {
        $this->source->position = $position;
    }

    /**
     * @return DateTime
     * @throws Exception
     */
    public function getUpdatedOn(): DateTime
    {
        return $this->updatedOn ?: $this->updatedOn = new DateTime($this->source->updated_on);
    }

    /**
     * @param DateTime $updatedOn
     */
    public function setUpdatedOn(DateTime $updatedOn)
    {
        $this->updatedOn = $updatedOn;
    }

    /**
     * @return string
     */
    public function getZipCode(): string
    {
        return $this->source->zip_code;
    }

    /**
     * @param string $zipCode
     */
    public function setZipCode(string $zipCode)
    {
        $this->source->zip_code = $zipCode;
    }

    /**
     * @param array $collectionRights
     */
    public function setCollectionRights(array $collectionRights)
    {
        $this->collectionRights = $collectionRights;
        $this->hasCollectionRights = true;
    }

    /**
     * @return array
     */
    public function getCollectionRights(): array
    {
        return $this->collectionRights;
    }

    /**
     * @return bool
     */
    public function hasCollectionRights(): bool
    {
        return $this->hasCollectionRights;
    }


    /**
     * @param array $collectionDemands
     */
    public function setCollectionDemands(array $collectionDemands)
    {
        $this->collectionDemands = $collectionDemands;
        $this->hasCollectionDemands = true;
    }

    /**
     * @return array
     */
    public function getCollectionDemands(): array
    {
        return $this->collectionDemands;
    }

    /**
     * @return bool
     */
    public function hasCollectionDemands(): bool
    {
        return $this->hasCollectionDemands;
    }
}
