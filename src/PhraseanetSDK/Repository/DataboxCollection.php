<?php

namespace PhraseanetSDK\Repository;

use PhraseanetSDK\Exception\RuntimeException;
use PhraseanetSDK\Entity\Databox;
use Doctrine\Common\Collections\ArrayCollection;

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
        $response = $this->query('GET', sprintf('/databoxes/%d/collections/', $databoxId));

        if (true !== $response->hasProperty('collections')) {
            throw new RuntimeException('Missing "collections" property in response content');
        }

        $databoxCollections = new ArrayCollection();

        foreach ($response->getProperty('collections') as $databoxCollectionDatas) {
            $databoxCollections->add($this->em->hydrateEntity($this->em->getEntity('databoxCollection'), $databoxCollectionDatas));
        }

        return $databoxCollections;
    }
}
