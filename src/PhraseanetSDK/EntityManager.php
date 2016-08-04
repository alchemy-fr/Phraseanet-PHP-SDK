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

use PhraseanetSDK\Http\ApiClient;
use PhraseanetSDK\Http\APIGuzzleAdapter;
use PhraseanetSDK\AbstractRepository;
use PhraseanetSDK\Http\Client;
use PhraseanetSDK\Orders\OrderRepository;
use PhraseanetSDK\Search\SearchRepository;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class EntityManager
{
    /**
     * @var APIGuzzleAdapter
     */
    private $client;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var AbstractRepository[]
     */
    private $repositories = array();

    /**
     * @param ApiClient $client
     * @param LoggerInterface $logger
     */
    public function __construct(ApiClient $client, LoggerInterface $logger = null) {
        $this->client = $client;
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
     * @deprecated This method will be removed in the next major release of the SDK. Use EntityManager::getClient().
     */
    public function getAdapter()
    {
        return $this->getClient();
    }

    /**
     * @return ApiClient
     */
    public function getClient()
    {
        return $this->client;
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
            return $this->repositories['search'] = new SearchRepository($this, $this->client);
        }

        if ($name == 'orders') {
            return $this->repositories['orders'] = new OrderRepository($this, $this->client);
        }

        if (!class_exists($objectName)) {
            throw new Exception\InvalidArgumentException(
                sprintf('Class %s does not exists', $objectName)
            );
        }

        return $this->repositories[$name] = new $objectName($this);
    }
}
