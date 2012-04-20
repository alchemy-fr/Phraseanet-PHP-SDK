<?php

namespace Alchemy\Sdk\Tools\Entity;

use Alchemy\Sdk\Exception;
use Alchemy\Sdk\Tools\Repository\Factory as RepoFactory;
use Alchemy\Sdk\Tools\Entity\Factory as EntityFactory;
use Doctrine\Common\Collections\ArrayCollection;

class Manager
{
    private $client;
    
    public function __construct(\PhraseanetApi $client)
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
}
