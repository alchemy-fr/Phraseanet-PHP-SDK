<?php

namespace PhraseanetSDK\Tools\Entity;

use PhraseanetSDK\Exception;
use PhraseanetSDK\Client;
use PhraseanetSDK\Tools\Repository\Factory as RepoFactory;
use PhraseanetSDK\Tools\Entity\Factory as EntityFactory;
use Doctrine\Common\Collections\ArrayCollection;

class Manager
{
    private $client;
    
    public function __construct(Client $client)
    {
        $this->client = $client;
    }
    
    public function getRepository($type)
    {
        return RepoFactory::factory($type, $this);
    }
    
    public function getEntity($type)
    {
        return EntityFactory::factory($type);
    }
    
    public function getClient()
    {
        return $this->client;
    }
}
