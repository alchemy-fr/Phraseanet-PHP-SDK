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
use PhraseanetSDK\Entity\DataboxCollection as DataboxCollectionEntity;
use PhraseanetSDK\Exception\NotFoundException;
use PhraseanetSDK\Exception\RuntimeException;
use PhraseanetSDK\Exception\TokenExpiredException;
use PhraseanetSDK\Exception\UnauthorizedException;

class DataboxCollection extends AbstractRepository
{
    /**
     * Find all collection in the provided databox
     *
     * @param integer $databoxId the databox id
     * @return ArrayCollection|DataboxCollectionEntity[]
     * @throws RuntimeException
     * @throws UnauthorizedException
     * @throws TokenExpiredException
     * @throws NotFoundException
     */
    public function findByDatabox(int $databoxId)
    {
        $response = $this->query('GET', sprintf('v1/databoxes/%d/collections/', $databoxId));

        if (true !== $response->hasProperty('collections')) {
            throw new RuntimeException('Missing "collections" property in response content');
        }

        return new ArrayCollection(DataboxCollectionEntity::fromList(
            $response->getProperty('collections')
        ));
    }

    /**
     * Finds a collection in all available databoxes
     *
     * @param integer $baseId The base ID of the collection
     * @return DataboxCollectionEntity
     * @throws RuntimeException
     * @throws UnauthorizedException
     * @throws TokenExpiredException
     * @throws NotFoundException
     */
    public function find(int $baseId): DataboxCollectionEntity
    {
        $response = $this->query('GET', sprintf('v1/collections/%d/', $baseId));

        if ($response->hasProperty(('collection')) !== true) {
            throw new RuntimeException('Missing "collection" property in response content');
        }

        return DataboxCollectionEntity::fromValue($response->getProperty('collection'));
    }
}
