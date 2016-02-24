<?php

/*
 * This file is part of Phraseanet SDK.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhraseanetSDK\Repository;

use PhraseanetSDK\AbstractRepository;
use PhraseanetSDK\EntityHydrator;
use PhraseanetSDK\Exception\RuntimeException;

class User extends AbstractRepository
{
    /**
     * @return \PhraseanetSDK\Entity\User
     * @throws \PhraseanetSDK\Exception\NotFoundException
     * @throws \PhraseanetSDK\Exception\UnauthorizedException
     * @deprecated Use User::me() instead
     */
    public function findMe()
    {
        return $this->me();
    }

    /**
     * @return \PhraseanetSDK\Entity\User
     * @throws \PhraseanetSDK\Exception\NotFoundException
     * @throws \PhraseanetSDK\Exception\UnauthorizedException
     */
    public function me()
    {
        $response = $this->query('GET', 'v1/me/');

        if (!$response->hasProperty('user')) {
            throw new RuntimeException('Missing "user" property in response content');
        }

        /** @var \PhraseanetSDK\Entity\User $user */
        $user = new \PhraseanetSDK\Entity\User($response->getProperty('user'));

        if ($response->hasProperty('collections')) {
            $user->setCollectionRights($response->getProperty('collections'));
        }

        if ($response->hasProperty('demands')) {
            $user->setCollectionDemands($response->getProperty('demands'));
        }

        return $user;
    }

    public function requestCollections(array $collections)
    {
        $response = $this->query('POST', 'v1/me/request-collections/', array(), $collections, array(
            'Content-Type' => 'application/json'
        ));

        if (!$response->hasProperty('demands')) {
            throw new RuntimeException('Missing "demands" property in response content');
        }

        return $response->getProperty('demands');
    }

    /**
     * @param $emailAddress
     * @return string
     * @throws \PhraseanetSDK\Exception\NotFoundException
     * @throws \PhraseanetSDK\Exception\UnauthorizedException
     */
    public function requestPasswordReset($emailAddress)
    {
        $response = $this->query('POST', 'v1/accounts/reset-password/' . $emailAddress . '/');

        if (!$response->hasProperty('reset_token')) {
            throw new RuntimeException('Missing "token" property in response content');
        }

        return (string)$response->getProperty('reset_token');
    }

    /**
     * @param $token
     * @param $password
     * @return bool
     * @throws \PhraseanetSDK\Exception\NotFoundException
     * @throws \PhraseanetSDK\Exception\UnauthorizedException
     */
    public function resetPassword($token, $password)
    {
        $response = $this->query('POST', 'v1/accounts/update-password/' . $token . '/', array(), array(
            'password' => $password
        ));

        if (!$response->hasProperty('success')) {
            throw new RuntimeException('Missing "success" property in response content');
        }

        return (bool)$response->getProperty('success');
    }

    public function updatePassword($currentPassword, $newPassword)
    {
        $response = $this->query('POST', 'v1/me/update-password/', array(), array(
            'oldPassword' => $currentPassword,
            'password' => array(
                'password' => $newPassword,
                'confirm' => $newPassword
            )
        ), array('Content-Type' => 'application/json'));

        if (!$response->hasProperty('success')) {
            throw new RuntimeException('Missing "success" property in response content');
        }

        return (bool)$response->getProperty('success');
    }

    /**
     * @param \PhraseanetSDK\Entity\User $user
     * @param $password
     * @param int[] $collections
     * @return string
     * @throws \PhraseanetSDK\Exception\NotFoundException
     * @throws \PhraseanetSDK\Exception\UnauthorizedException
     */
    public function createUser(\PhraseanetSDK\Entity\User $user, $password, array $collections = null)
    {
        $data = array(
            'email' => $user->getEmail(),
            'password' => $password,
            'gender' => $user->getGender(),
            'firstname' => $user->getFirstName(),
            'lastname' => $user->getLastName(),
            'city' => $user->getCity(),
            'tel' => $user->getPhone(),
            'company' => $user->getCompany(),
            'job' => $user->getJob(),
            'notifications' => false
        );

        if ($collections !== null) {
            $data['collections'] = $collections;
        }

        $response = $this->query(
            'POST',
            'v1/accounts/access-demand/',
            array(),
            $data,
            array('Content-Type' => 'application/json')
        );

        if (!$response->hasProperty('user')) {
            throw new \RuntimeException('Missing "user" property in response content');
        }

        if (!$response->hasProperty('token')) {
            throw new \RuntimeException('Missing "token" property in response content');
        }

        return (string)$response->getProperty('token');
    }

    public function updateUser(\PhraseanetSDK\Entity\User $user)
    {
        $data = array(
            'email' => $user->getEmail(),
            'gender' => $user->getGender(),
            'firstname' => $user->getFirstName(),
            'lastname' => $user->getLastName(),
            'city' => $user->getCity(),
            'tel' => $user->getPhone(),
            'company' => $user->getCompany(),
            'job' => $user->getJob(),
            'notifications' => false
        );

        $response = $this->query(
            'POST',
            'v1/me/update-account/',
            array(),
            $data,
            array('Content-Type' => 'application/json')
        );

        if (!$response->hasProperty('success')) {
            throw new RuntimeException('Missing "success" property in response content');
        }

        return (bool)$response->getProperty('success');
    }

    public function deleteAccount()
    {
        $this->query('DELETE', 'me/');
    }

    /**
     * @param $token
     * @return bool
     * @throws \PhraseanetSDK\Exception\NotFoundException
     * @throws \PhraseanetSDK\Exception\UnauthorizedException
     */
    public function unlockAccount($token)
    {
        $response = $this->query('POST', 'v1/accounts/unlock/' . $token . '/', array(), array());

        if (!$response->hasProperty('success')) {
            throw new \RuntimeException('Missing "success" property in response content');
        }

        return (bool)$response->getProperty('success');
    }
}
