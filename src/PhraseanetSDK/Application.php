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

    /** @var APIGuzzleAdapter[] */
    private $adapters = array();

    /** @var array An array of loaders */
    private $uploaders = array();

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

    /**
     * {@inheritdoc}
     */
    public function getUploader($token)
    {
        if ('' === trim($token)) {
            throw new InvalidArgumentException('Token can not be empty.');
        }

        if (isset($this->uploaders[$token])) {
            return $this->uploaders[$token];
        }

        return $this->uploaders[$token] = new Uploader($this->getAdapterByToken($token), $this->getEntityManager($token));
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityManager($token, array $options = array())
    {
        if ('' === trim($token)) {
            throw new InvalidArgumentException('Token can not be empty.');
        }

        if (isset($this->ems[$token])) {
            return $this->ems[$token];
        }

        return $this->ems[$token] = new EntityManager($this->getAdapterByToken($token), $options);
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

        return $this->monitors[$token] = new Monitor($this->getAdapterByToken($token));
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
     * @param GuzzleAdapter $adapter
     *
     * @return Application
     *
     * @throws InvalidArgumentException In case a required parameter is missing
     */
    public static function create(array $config, GuzzleAdapter $adapter = null)
    {
        foreach (array('client-id', 'secret') as $key) {
            if (!isset($config[$key]) || !is_string($config[$key])) {
                throw new InvalidArgumentException(sprintf('Missing or invalid parameter "%s"', $key));
            }
        }

        if (null === $adapter) {
            if (!isset($config['url']) || !is_string($config['url'])) {
                throw new InvalidArgumentException(sprintf('Missing or invalid parameter "url"'));
            }

            $adapter = GuzzleAdapter::create($config['url']);
        }

        return new static(
            $adapter,
            $config['client-id'],
            $config['secret']
        );
    }

    /**
     * Activate extended graph object by adding required accept headers.
     * This results in bigger response message but less requests to get
     * relation of queried object.
     *
     * @param $mode
     */
    public function setExtendedMode($mode)
    {
        $this->adapter->setExtended($mode);
    }

    private function getAdapterByToken($token)
    {
        if (!isset($this->adapters[$token])) {
            $this->adapters[$token] = new APIGuzzleAdapter(
                new ConnectedGuzzleAdapter(
                    $token,
                    $this->adapter
                )
            );
        }

        return $this->adapters[$token];
    }
}
