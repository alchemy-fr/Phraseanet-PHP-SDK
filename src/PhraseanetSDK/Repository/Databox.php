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

use Doctrine\Common\Collections\ArrayCollection;
use PhraseanetSDK\AbstractRepository;
use PhraseanetSDK\Entity\Databox as DataboxEntity;
use PhraseanetSDK\Exception\NotFoundException;
use PhraseanetSDK\Exception\RuntimeException;
use PhraseanetSDK\Exception\TokenExpiredException;
use PhraseanetSDK\Exception\UnauthorizedException;

class Databox extends AbstractRepository
{
    /**
     * Find All databoxes
     *
     * @return ArrayCollection
     * @throws RuntimeException
     * @throws UnauthorizedException
     * @throws TokenExpiredException
     * @throws NotFoundException
     */
    public function findAll(): ArrayCollection
    {
        $response = $this->query('GET', 'v1/databoxes/list/');

        if (true !== $response->hasProperty('databoxes')) {
            throw new RuntimeException('Missing "databoxes" property in response content');
        }

        return new ArrayCollection(DataboxEntity::fromList($response->getProperty('databoxes')));
    }
}
