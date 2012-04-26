<?php

namespace PhraseanetSDK\Repository;

use PhraseanetSDK\Exception\ApiResponseException;
use PhraseanetSDK\Entity;
use PhraseanetSDK\Tools\Entity\Factory;
use PhraseanetSDK\Tools\Entity\Hydrator;
use Doctrine\Common\Collections\ArrayCollection;

class Subdef extends RepositoryAbstract
{

    public function findAll(Entity\Record $record)
    {
        $path = sprintf('/records/%d/%d/embed/', $record->getDataboxId(), $record->getRecordId());

        $response = $this->getClient()->call($path, array(), 'GET');

        $subdefCollection = new ArrayCollection();

        if ($response->isOk()) {
            foreach ($response->getResult()->embed as $name => $subdefDatas) {
                $subdef = $this->em->hydrateEntity($this->em->getEntity('subdef'), $subdefDatas);

                $subdef->setName($name);

                $subdefCollection->add($subdef);
            }

            return $subdefCollection;
        } else {
            throw new ApiResponseException(
                $response->getErrorMessage(), $response->getHttpStatusCode());
        }
    }

    public function findByName(Entity\Record $record, $name)
    {
        $subdefs = $this->findAll($record);

        foreach ($subdefs as $subdef) {
            if ($subdef->getName() === $name) {
                return $subdef;
            }
        }

        throw new ApiResponseException(
            sprintf('%s subdef name not found', $name), 404);
    }
}
