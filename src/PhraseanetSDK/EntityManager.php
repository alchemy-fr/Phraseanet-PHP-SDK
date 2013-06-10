<?php

/*
 * This file is part of Phraseanet SDK.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhraseanetSDK;

use PhraseanetSDK\Http\APIGuzzleAdapter;
use PhraseanetSDK\Repository\Factory as RepoFactory;
use PhraseanetSDK\Entity\Factory as EntityFactory;
use PhraseanetSDK\Entity\EntityHydrator;
use PhraseanetSDK\Entity\EntityInterface;

class EntityManager
{
    private $adapter;
    private $repositories;

    /**
     *
     * @param APIGuzzleAdapter $adapter
     */
    public function __construct(APIGuzzleAdapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Get a repository by its name
     *
     * @param  string                                        $name
     * @return \PhraseanetSDK\Repository\RepositoryInterface
     */
    public function getRepository($name)
    {
        if (isset($this->repositories[$name])) {
            return $this->repositories[$name];
        }

        $this->repositories[$name] = RepoFactory::build($name, $this);

        return $this->repositories[$name];
    }

    /**
     * Return a new entity by its name
     *
     * @param  string          $name The name of the entity
     * @return EntityInterface
     */
    public function getEntity($name)
    {
        return EntityFactory::build($name, $this);
    }

    /**
     * Hydrates an entity with datas
     *
     * @param EntityInterface $entity
     * @param \stdClass       $datas
     *
     * @return EntityInterface
     */
    public function HydrateEntity(EntityInterface $entity, \stdClass $datas)
    {
        return EntityHydrator::hydrate($entity, $datas, $this);
    }

    /**
     * Return the client attached to this entity manager
     *
     * @return APIGuzzleAdapter
     */
    public function getAdapter()
    {
        return $this->adapter;
    }
}
