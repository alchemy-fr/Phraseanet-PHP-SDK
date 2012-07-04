<?php

namespace PhraseanetSDK\Repository;

use PhraseanetSDK\Exception\RuntimeException;
use Doctrine\Common\Collections\ArrayCollection;

class Databox extends AbstractRepository
{

    /**
     * Find All databoxes
     *
     * @return ArrayCollection
     * @throws RuntimeException
     */
    public function findAll()
    {
        $response = $this->query('GET', '/databoxes/list/');

        if (true !== $response->hasProperty('databoxes')) {
            throw new RuntimeException('Missing "databoxes" property in response content');
        }

        $databoxCollection = new ArrayCollection();

        foreach ($response->getProperty('databoxes') as $databoxDatas) {
            $databoxCollection->add($this->em->hydrateEntity($this->em->getEntity('databox'), $databoxDatas));
        }

        return $databoxCollection;
    }
}
