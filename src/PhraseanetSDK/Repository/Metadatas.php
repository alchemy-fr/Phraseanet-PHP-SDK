<?php

namespace PhraseanetSDK\Repository;

use PhraseanetSDK\Exception\ApiResponseException;
use PhraseanetSDK\Entity;
use PhraseanetSDK\Tools\Entity\Factory;
use PhraseanetSDK\Tools\Entity\Hydrator;
use Doctrine\Common\Collections\ArrayCollection;

class Metadatas extends RepositoryAbstract
{

    public function findAll(Entity\Record $record)
    {
        $path = sprintf('/records/%d/%d/metadatas/', $record->getDataboxId(), $record->getRecordId());

        $response = $this->getClient()->call($path, array(), 'GET');

        $metaCollection = new ArrayCollection();

        if ($response->isOk()) {
            foreach ($response->getResult()->metadatas as $metaDatas) {
                $meta = $this->em->hydrateEntity($this->em->getEntity('metadatas'), $metaDatas);

                $metaCollection->add($meta);
            }

            return $metaCollection;
        } else {
            throw new ApiResponseException(
                $response->getErrorMessage(), $response->getHttpStatusCode());
        }
    }
}
