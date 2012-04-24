<?php

namespace PhraseanetSDK\Repository;

use PhraseanetSDK\Exception\ApiResponseException;
use PhraseanetSDK\Tools\Entity\Factory;
use PhraseanetSDK\Tools\Entity\Hydrator;
use PhraseanetSDK\Tools\Repository\RepositoryAbstract;
use Doctrine\Common\Collections\ArrayCollection;

class Feed extends RepositoryAbstract
{

    public function findById($id, $offset = 0, $perPage = 5)
    {
        $path = sprintf('/feeds/%d/content/', $id, $offset, $perPage);

        $response = $this->getClient()->call($path, array(
            'offset_start' => $offset,
            'per_page' => $perPage
                ), 'GET');

        $feed = null;

        if ($response->isOk())
        {
            if ($feedDatas = $response->getResult()->feed)
            {
                $feed = Hydrator::hydrate(
                                Factory::factory('feed')
                                , $feedDatas
                );

                $entriesCollection = new ArrayCollection();

                foreach ($response->getResult()->entries->entries as $entryId => $entryDatas)
                {
                    $entry = Hydrator::hydrate(
                                    Factory::factory('entry')
                                    , $entryDatas
                    );

                    $entry->setId($entryId);

                    $entriesCollection->add($entry);
                }

                $feed->setEntries($entriesCollection);
            }

            return $feed;
        }
        else
        {
            throw new ApiResponseException(
                    $response->getErrorMessage(), $response->getHttpStatusCode());
        }
    }

    public function findAll()
    {
        $response = $this->getClient()->call('/feeds/list/', array(), 'GET');

        $feedCollection = new ArrayCollection();

        if ($response->isOk())
        {
            foreach ($response->getResult()->feeds as $feedDatas)
            {
                $feed = Hydrator::hydrate($this->em->getEntity('feed'), $feedDatas);

                $feedCollection->add($feed);
            }

            return $feedCollection;
        }
        else
        {
            throw new ApiResponseException(
                    $response->getErrorMessage(), $response->getHttpStatusCode());
        }
    }

}
