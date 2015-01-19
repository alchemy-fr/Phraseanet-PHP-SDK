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

class User
{
    /**
     * @ApiField(bind_to="id", type="int")
     */
    protected $id;
    /**
     * @ApiField(bind_to="email", type="string")
     */
    protected $email;
    /**
     * @ApiField(bind_to="login", type="string")
     */
    protected $login;
    /**
     * @ApiField(bind_to="first_name", type="string")
     */
    protected $firstName;
    /**
     * @ApiField(bind_to="last_name", type="string")
     */
    protected $lastName;
    /**
     * @ApiField(bind_to="display_name", type="string")
     */
    protected $displayName;
    /**
     * @ApiField(bind_to="gender", type="string")
     */
    protected $gender;
    /**
     * @ApiField(bind_to="address", type="string")
     */
    protected $address;
    /**
     * @ApiField(bind_to="zip_code", type="string")
     */
    protected $zipCode;
    /**
     * @ApiField(bind_to="city", type="string")
     */
    protected $city;
    /**
     * @ApiField(bind_to="country", type="string")
     */
    protected $country;
    /**
     * @ApiField(bind_to="phone", type="string")
     */
    protected $phone;
    /**
     * @ApiField(bind_to="fax", type="string")
     */
    protected $fax;
    /**
     * @ApiField(bind_to="job", type="string")
     */
    protected $job;
    /**
     * @ApiField(bind_to="position", type="string")
     */
    protected $position;
    /**
     * @ApiField(bind_to="company", type="string")
     */
    protected $company;
    /**
     * @ApiField(bind_to="geoname_id", type="int")
     */
    protected $geonameId;
    /**
     * @ApiField(bind_to="locale", type="string")
     */
    protected $locale;
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
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getCompany()
    {
        return $this->company;
    }

    public function setCompany($company)
    {
        $this->company = $company;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return mixed
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;
    }

    /**
     * @return mixed
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getFax()
    {
        return $this->fax;
    }

    public function setFax($fax)
    {
        $this->fax = $fax;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    public function getGender()
    {
        return $this->gender;
    }

    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * @return mixed
     */
    public function getGeonameId()
    {
        return $this->geonameId;
    }

    public function setGeonameId($geonameId)
    {
        $this->geonameId = $geonameId;
    }

    /**
     * @return mixed
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
     * @return mixed
     */
    public function getJob()
    {
        return $this->job;
    }

    public function setJob($job)
    {
        $this->job = $job;
    }

    /**
     * @return mixed
     */
    public function getLastConnection()
    {
        return $this->lastConnection;
    }

    public function setLastConnection($lastConnection)
    {
        $this->lastConnection = $lastConnection;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return mixed
     */
    public function getLocale()
    {
        return $this->locale;
    }

    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * @return mixed
     */
    public function getLogin()
    {
        return $this->login;
    }

    public function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getPosition()
    {
        return $this->position;
    }

    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * @return mixed
     */
    public function getUpdatedOn()
    {
        return $this->updatedOn;
    }

    public function setUpdatedOn($updatedOn)
    {
        $this->updatedOn = $updatedOn;
    }

    /**
     * @return mixed
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;
    }
}
