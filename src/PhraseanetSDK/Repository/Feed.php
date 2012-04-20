<?php

namespace PhraseanetSDK\Repository;

use PhraseanetSDK\Tools\Repository\RepositoryAbstract;
use PhraseanetSDK\Exception\ApiRequestException;
use Doctrine\Common\Collections\ArrayCollection;

class Feed extends RepositoryAbstract
{

    public function findById($id, $offset = 0, $perPage = 5)
    {
        $path = sprintf('/feeds/%d/%d/%d', $id, $offset, $perPage);
        
        $response = $this->getClient()->call($path, array(), 'GET');
        
        $feedCollection = new ArrayCollection();
        
        if($response->isOk())
        {
            $collection = $this->findAll()->filter(function($el)
                    {
                        if ($el->getId() == $id)
                        {
                            return true;
                        }
                    });
        }
        
        return $feedCollection;
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
