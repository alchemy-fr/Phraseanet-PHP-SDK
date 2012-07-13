<?php

namespace PhraseanetSDK;

use PhraseanetSDK\Client;
use PhraseanetSDK\Repository\Factory as RepoFactory;
use PhraseanetSDK\Entity\Factory as EntityFactory;
use PhraseanetSDK\Entity\EntityHydrator;
use PhraseanetSDK\Entity\EntityInterface;

class EntityManager
{
    private $client;
    private $repositories;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getRepository($name)
    {
        if (isset($this->repositories[$name])) {
            return $this->repositories[$name];
        }

        $this->repositories[$name] = RepoFactory::build($name, $this);

        return $this->repositories[$name];
    }

    public function getEntity($type)
    {
        return EntityFactory::build($type, $this);
    }

    public function HydrateEntity(EntityInterface $entity, $datas)
    {
        return EntityHydrator::hydrate($entity, $datas, $this);
    }

    public function getClient()
    {
        return $this->client;
    }
}
