<?php

namespace PhraseanetSDK\Tools\Entity;

use PhraseanetSDK\Client;
use PhraseanetSDK\Tools\Repository\Factory as RepoFactory;
use PhraseanetSDK\Tools\Entity\Factory as EntityFactory;
use PhraseanetSDK\Tools\Entity\Hydrator;
use PhraseanetSDK\Entity\EntityInterface;

class Manager
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getRepository($type)
    {
        return RepoFactory::build($type, $this);
    }

    public function getEntity($type)
    {
        return EntityFactory::build($type, $this);
    }

    public function HydrateEntity(EntityInterface $entity, $datas)
    {
        return Hydrator::hydrate($entity, $datas, $this);
    }

    public function getClient()
    {
        return $this->client;
    }
}
