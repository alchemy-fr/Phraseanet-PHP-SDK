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
use Doctrine\Common\Collections\ArrayCollection;
use PhraseanetSDK\EntityHydrator;

class User extends AbstractRepository
{
    public function findMe()
    {
        $response = $this->query('GET', 'me');

        if (true !== $response->hasProperty('user')) {
            throw new RuntimeException('Missing "user" property in response content');
        }
        
        return EntityHydrator::hydrate('user', $response->getProperty('user'), $this->em);

    }
}
