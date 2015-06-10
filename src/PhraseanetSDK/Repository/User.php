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

use PhraseanetSDK\Exception\RuntimeException;
use PhraseanetSDK\EntityHydrator;

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
        $response = $this->query('GET', 'me');

        if (! $response->hasProperty('user')) {
            throw new RuntimeException('Missing "user" property in response content');
        }

        $user = EntityHydrator::hydrate('user', $response->getProperty('user'), $this->em);

        if ($response->hasProperty('collections')) {
            $user->setAccessibleCollections($response->getProperty('collections'));
        }

        return $user;
    }
}
