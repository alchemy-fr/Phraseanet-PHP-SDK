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
use PhraseanetSDK\Exception\NotFoundException;
use PhraseanetSDK\Exception\RuntimeException;
use PhraseanetSDK\Exception\TokenExpiredException;
use PhraseanetSDK\Exception\UnauthorizedException;
use PhraseanetSDK\Entity\User as UserEntity;

class User extends AbstractRepository
{
    /**
     * @return UserEntity
     * @throws RuntimeException
     * @throws UnauthorizedException
     * @throws TokenExpiredException
     * @throws NotFoundException
     * @deprecated Use User::me() instead
     */
    public function findMe()
    {
        return $this->me();
    }

    /**
     * @return UserEntity
     * @throws RuntimeException
     * @throws UnauthorizedException
     * @throws TokenExpiredException
     * @throws NotFoundException
     */
    public function me()
    {
        $response = $this->query('GET', 'v1/me/');

        if (!$response->hasProperty('user')) {
            throw new RuntimeException('Missing "user" property in response content');
        }

        /** @var UserEntity $user */
        $user = new UserEntity($response->getProperty('user'));

        if ($response->hasProperty('collections')) {
            $user->setCollectionRights($response->getProperty('collections'));
        }

        if ($response->hasProperty('demands')) {
            $user->setCollectionDemands($response->getProperty('demands'));
        }

        return $user;
    }

    /**
     * @param array $collections
     * @return \stdClass|\stdClass[]|null
     * @throws RuntimeException
     * @throws UnauthorizedException
     * @throws TokenExpiredException
     * @throws NotFoundException
     */
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
     * @throws RuntimeException
     * @throws UnauthorizedException
     * @throws TokenExpiredException
     * @throws NotFoundException
     */
    public function requestPasswordReset(string $emailAddress): string
    {
        $response = $this->query('POST', 'v1/accounts/reset-password/' . urlencode($emailAddress) . '/');

        if (!$response->hasProperty('reset_token')) {
            throw new RuntimeException('Missing "token" property in response content');
        }

        return (string)$response->getProperty('reset_token');
    }

    /**
     * @param string $token
     * @param string $password
     * @return bool
     * @throws RuntimeException
     * @throws UnauthorizedException
     * @throws TokenExpiredException
     * @throws NotFoundException
     */
    public function resetPassword(string $token, string $password): bool
    {
        $response = $this->query('POST', 'v1/accounts/update-password/' . $token . '/', array(), array(
            'password' => $password
        ));

        if (!$response->hasProperty('success')) {
            throw new RuntimeException('Missing "success" property in response content');
        }

        return (bool)$response->getProperty('success');
    }

    /**
     * @param string $currentPassword
     * @param string $newPassword
     * @return bool
     * @throws NotFoundException
     * @throws TokenExpiredException
     * @throws UnauthorizedException
     */
    public function updatePassword(string $currentPassword, string $newPassword): bool
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
     * @param UserEntity $user
     * @param string $password
     * @param int[] $collections
     * @return string
     * @throws NotFoundException
     * @throws UnauthorizedException
     * @throws TokenExpiredException
     */
    public function createUser(UserEntity $user, string $password, array $collections = null)
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
            throw new RuntimeException('Missing "user" property in response content');
        }

        if (!$response->hasProperty('token')) {
            throw new RuntimeException('Missing "token" property in response content');
        }

        return (string)$response->getProperty('token');
    }

    public function updateUser(UserEntity $user)
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

    /**
     * @throws NotFoundException
     * @throws TokenExpiredException
     * @throws UnauthorizedException
     */
    public function deleteAccount()
    {
        $this->query('DELETE', 'me/');
    }

    /**
     * @param string $token
     * @return bool
     * @throws NotFoundException
     * @throws UnauthorizedException
     * @throws TokenExpiredException
     */
    public function unlockAccount(string $token): bool
    {
        $response = $this->query('POST', 'v1/accounts/unlock/' . $token . '/');

        if (!$response->hasProperty('success')) {
            throw new \RuntimeException('Missing "success" property in response content');
        }

        return (bool)$response->getProperty('success');
    }
}
