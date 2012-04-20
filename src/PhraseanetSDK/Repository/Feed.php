<?php

namespace PhraseanetSDK\Repository;

use PhraseanetSDK\Exception\ApiRequestException;
use PhraseanetSDK\Tools\Repository\Factory;
use PhraseanetSDK\Tools\Repository\Hydrator;
use PhraseanetSDK\Tools\Repository\RepositoryAbstract;
use Doctrine\Common\Collections\ArrayCollection;

class Feed extends RepositoryAbstract
{

    public function findById($id, $offset = 0, $perPage = 5)
    {
        $path = sprintf('/feeds/%d/content/%d/%d', $id, $offset, $perPage);

        $response = $this->getClient()->call($path, array(), 'GET');

        if ($response->isOk())
        {
            $entriesCollection = new ArrayCollection();

            if ($feedDatas = $response->getResult()->feed)
            {
                $feed = Hydrator::hydrate(
                                Factory::factory('feed')
                                , $feedDatas
                );
            }

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
        }
        return $feedCollection;
    }

}
