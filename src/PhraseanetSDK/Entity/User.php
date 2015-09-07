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
use PhraseanetSDK\Annotation\Id as Id;

class User
{

    public static function fromList(array $values)
    {
        $users = array();

        foreach ($values as $value) {
            $users[] = self::fromValue($value);
        }

        return $users;
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
     * @param \stdClass $source
     */
    public function __construct(\stdClass $source)
    {
        $this->source = $source;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->source->address;
    }

    /**
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->source->address = $address;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->source->city;
    }

    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->source->city = $city;
    }

    /**
     * @return string
     */
    public function getCompany()
    {
        return $this->source->company;
    }

    /**
     * @param string $company
     */
    public function setCompany($company)
    {
        $this->source->company = $company;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->source->country;
    }

    /**
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->source->country = $country;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedOn()
    {
        return $this->createdOn ?: $this->createdOn = new \DateTime($this->source->created_on);
    }

    /**
     * @param \DateTime $createdOn
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;
    }

    /**
     * @return string
     */
    public function getDisplayName()
    {
        return $this->source->display_name;
    }

    /**
     * @param string $displayName
     */
    public function setDisplayName($displayName)
    {
        $this->source->display_name = $displayName;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->source->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->source->email = $email;
    }

    /**
     * @return string
     */
    public function getFax()
    {
        return $this->source->fax;
    }

    /**
     * @param string $fax
     */
    public function setFax($fax)
    {
        $this->source->fax = $fax;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->source->first_name;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->source->first_name = $firstName;
    }

    /**
     * @return string
     */
    public function getGender()
    {
        return $this->source->gender;
    }

    /**
     * @param string $gender
     */
    public function setGender($gender)
    {
        $this->source->gender = $gender;
    }

    /**
     * @return string
     */
    public function getGeonameId()
    {
        return $this->source->geoname_id;
    }

    /**
     * @param string $geonameId
     */
    public function setGeonameId($geonameId)
    {
        $this->source->geoname_id = $geonameId;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->source->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->source->id = $id;
    }

    /**
     * @return string
     */
    public function getJob()
    {
        return $this->source->job;
    }

    /**
     * @param string $job
     */
    public function setJob($job)
    {
        $this->source->job = $job;
    }

    /**
     * @return \DateTime
     */
    public function getLastConnection()
    {
        return $this->lastConnection ?: $this->lastConnection = new \DateTime($this->source->last_connection);
    }

    /**
     * @param \DateTime $lastConnection
     */
    public function setLastConnection($lastConnection)
    {
        $this->lastConnection = $lastConnection;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->source->last_name;
    }

    /**
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->source->last_name = $lastName;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->source->locale;
    }

    /**
     * @param string $locale
     */
    public function setLocale($locale)
    {
        $this->source->locale = $locale;
    }

    /**
     * @return string
     */
    public function getLogin()
    {
        return $this->source->login;
    }

    /**
     * @param string $login
     */
    public function setLogin($login)
    {
        $this->source->login = $login;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->source->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->source->phone = $phone;
    }

    /**
     * @return string
     */
    public function getPosition()
    {
        return $this->source->position;
    }

    /**
     * @param string $position
     */
    public function setPosition($position)
    {
        $this->source->position = $position;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedOn()
    {
        return $this->updatedOn ?: $this->updatedOn = new \DateTime($this->source->updated_on);
    }

    /**
     * @param \DateTime $updatedOn
     */
    public function setUpdatedOn($updatedOn)
    {
        $this->updatedOn = $updatedOn;
    }

    /**
     * @return string
     */
    public function getZipCode()
    {
        return $this->source->zip_code;
    }

    /**
     * @param string $zipCode
     */
    public function setZipCode($zipCode)
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
    public function getCollectionRights()
    {
        return $this->collectionRights;
    }

    /**
     * @return bool
     */
    public function hasCollectionRights()
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
    public function getCollectionDemands()
    {
        return $this->collectionDemands;
    }

    /**
     * @return bool
     */
    public function hasCollectionDemands()
    {
        return $this->hasCollectionDemands;
    }
}
