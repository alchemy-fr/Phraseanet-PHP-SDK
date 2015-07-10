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

class DataboxCollection extends AbstractRepository
{
    /**
     * Find all collection in the provided databox
     *
     * @param  integer          $databoxId the databox id
     * @return ArrayCollection
     * @throws RuntimeException
     */
    public function findByDatabox($databoxId)
    {
        $response = $this->query('GET', sprintf('databoxes/%d/collections/', $databoxId));

        if (true !== $response->hasProperty('collections')) {
            throw new RuntimeException('Missing "collections" property in response content');
        }

        $databoxCollections = new ArrayCollection();

        foreach ($response->getProperty('collections') as $databoxCollectionData) {
            $databoxCollections->add($this->hydrateCollection($databoxCollectionData, $databoxId));
        }

        return $databoxCollections;
    }

    /**
     * Finds a collection in all available databoxes
     *
     * @param integer $baseId The base ID of the collection
     * @return \ProxyManager\Proxy\GhostObjectInterface
     * @throws \PhraseanetSDK\Exception\NotFoundException
     * @throws \PhraseanetSDK\Exception\UnauthorizedException
     */
    public function find($baseId)
    {
        $response = $this->query('GET', sprintf('collections/%d/', $baseId));

        if ($response->hasProperty(('collection')) !== true) {
            throw new RuntimeException('Missing "collection" property in response content');
        }

        return $this->hydrateCollection($response->getProperty('collection'), null);
    }

    private function hydrateCollection($collectionData, $databoxId = null)
    {
        /** @var \PhraseanetSDK\Entity\DataboxCollection $collection */
        $collection = EntityHydrator::hydrate('databoxCollection', $collectionData, $this->em);

        if (isset($collectionData->databox_id)) {
            $collection->setDataboxId($collectionData->databox_id);
        }
        elseif ($databoxId !== null) {
            $collection->setDataboxId($databoxId);
        }

        return $collection;
    }
}
