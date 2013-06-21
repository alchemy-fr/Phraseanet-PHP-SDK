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

use PhraseanetSDK\Http\GuzzleAdapter;
use PhraseanetSDK\Exception\InvalidArgumentException;
use PhraseanetSDK\Http\ConnectedGuzzleAdapter;
use PhraseanetSDK\Http\APIGuzzleAdapter;

/**
 * Phraseanet SDK Application
 */
class Application implements ApplicationInterface
{
    /** @var GuzzleAdapter */
    private $adapter;

    /** @var string Url */
    private $clientId;

    /** @var string Url */
    private $secret;

    /** @var array An array of EntityManager */
    private $ems = array();

    /** @var OAuth2Connector */
    private $connector;

    /** @var APIGuzzleAdapter */
    private $APIAdapter = array();

    /** @var array An array of loaders */
    private $loaders = array();

    public function __construct(GuzzleAdapter $adapter, $clientId, $secret)
    {
        $this->adapter = $adapter;
        $this->clientId = $clientId;
        $this->secret = $secret;
    }

    /**
     * {@inheritdoc}
     */
    public function getOauth2Connector()
    {
        if (null !== $this->connector) {
            return $this->connector;
        }

        return $this->connector = new OAuth2Connector($this->adapter, $this->clientId, $this->secret);
    }

    public function getLoader($token)
    {
        if ('' === trim($token)) {
            throw new InvalidArgumentException('Token can not be empty.');
        }

        if (isset($this->loaders[$token])) {
            return $this->loaders[$token];
        }

        return $this->loaders[$token] = new Loader($this->getAPIGuzzleAdapter($token), $this->getEntityManager($token));
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityManager($token)
    {
        if ('' === trim($token)) {
            throw new InvalidArgumentException('Token can not be empty.');
        }

        if (isset($this->ems[$token])) {
            return $this->ems[$token];
        }

        return $this->ems[$token] = new EntityManager($this->getAPIGuzzleAdapter($token));
    }

    /**
     * {@inheritdoc}
     */
    public function getMonitor($token)
    {
        if ('' === trim($token)) {
            throw new InvalidArgumentException('Token can not be empty.');
        }

        if (isset($this->monitors[$token])) {
            return $this->monitors[$token];
        }

        return $this->monitors[$token] = new Monitor($this->getAPIGuzzleAdapter($token));
    }

    /**
     * Returns the guzzle adapter
     *
     * @return GuzzleAdapter
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * Creates the application.
     *
     * @param array $config
     * @param array $cache
     * @param array $plugins
     *
     * @return Application
     *
     * @throws InvalidArgumentException In case a parameter is missing
     */
    public static function create(array $config, array $cache = array(), array $plugins = array())
    {
        return new static(
            GuzzleAdapter::create($config, $cache, $plugins),
            $config['client-id'],
            $config['secret']
        );
    }

    private function getAPIGuzzleAdapter($token)
    {
        if (!isset($this->APIAdapter[$token])) {
            $this->APIAdapter[$token] = new APIGuzzleAdapter(
                new ConnectedGuzzleAdapter(
                    $token, $this->adapter
                )
            );
        }

        return $this->APIAdapter[$token];
    }
}
