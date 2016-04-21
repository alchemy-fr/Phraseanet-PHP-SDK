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
use PhraseanetSDK\AbstractRepository;
use PhraseanetSDK\Orders\OrderRepository;
use PhraseanetSDK\Search\SearchRepository;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class EntityManager
{
    /**
     * @var APIGuzzleAdapter
     */
    private $adapter;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var AbstractRepository[]
     */
    private $repositories = array();

    /**
     * @param APIGuzzleAdapter $adapter
     * @param LoggerInterface $logger
     */
    public function __construct(
        APIGuzzleAdapter $adapter,
        LoggerInterface $logger = null
    ) {
        $this->adapter = $adapter;
        $this->logger = $logger ?: new NullLogger();
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
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

    /**
     * Get a repository by its name
     *
     * @param  string $name
     * @return AbstractRepository
     */
    public function getRepository($name)
    {
        if (isset($this->repositories[$name])) {
            return $this->repositories[$name];
        }

        $className = ucfirst($name);
        $objectName = sprintf('\\PhraseanetSDK\\Repository\\%s', $className);

        if ($name == 'search') {
            return $this->repositories['search'] = new SearchRepository($this, $this->adapter);
        }

        if ($name == 'orders') {
            return $this->repositories['orders'] = new OrderRepository($this, $this->adapter);
        }

        if (!class_exists($objectName)) {
            throw new Exception\InvalidArgumentException(
                sprintf('Class %s does not exists', $objectName)
            );
        }

        return $this->repositories[$name] = new $objectName($this);
    }
}
