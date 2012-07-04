<?php

namespace PhraseanetSDK\Repository;

use PhraseanetSDK\Exception\RuntimeException;
use PhraseanetSDK\Entity\Databox;
use Doctrine\Common\Collections\ArrayCollection;

class DataboxStatus extends AbstractRepository
{

    /**
     * The status of the desired databox
     *
     * @param  integer          $databoxId the databox id
     * @return ArrayCollection
     * @throws RuntimeException
     */
    public function findByDatabox($databoxId)
    {
        $response = $this->query('GET', sprintf('/databoxes/%d/status/', $databoxId));

        if (true !== $response->hasProperty('status')) {
            throw new RuntimeException('Missing "status" property in response content');
        }

        $databoxStatusCollection = new ArrayCollection();

        foreach ($response->getProperty('status') as $databoxStatusDatas) {
            $databoxStatusCollection->add($this->em->hydrateEntity($this->em->getEntity('databoxStatus'), $databoxStatusDatas));
        }

        return $databoxStatusCollection;
    }
}
