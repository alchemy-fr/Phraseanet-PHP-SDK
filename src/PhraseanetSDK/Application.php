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

use PhraseanetSDK\Exception\InvalidArgumentException;
use PhraseanetSDK\Http\ApiClient;
use PhraseanetSDK\Http\APIGuzzleAdapter;
use PhraseanetSDK\Http\Client;
use PhraseanetSDK\Http\ClientFactory;
use PhraseanetSDK\Http\ConnectedGuzzleAdapter;
use PhraseanetSDK\Http\Endpoint;
use PhraseanetSDK\Http\Guzzle\GuzzleClient;
use Psr\Log\NullLogger;

/**
 * Phraseanet SDK Application
 */
class Application implements ApplicationInterface
{
    /**
     * Creates the application.
     *
     * @param array $config
     * @param ApiClient $client
     * @return Application
     */
    public static function create(array $config, ApiClient $client = null)
    {
        foreach (array('client-id', 'secret') as $key) {
            if (!isset($config[$key]) || !is_string($config[$key])) {
                throw new InvalidArgumentException(sprintf('Missing or invalid parameter "%s"', $key));
            }
        }

        if (null === $client) {
            if (!isset($config['url']) || !is_string($config['url'])) {
                throw new InvalidArgumentException(sprintf('Missing or invalid parameter "url"'));
            }

            $clientFactory = new ClientFactory();
            $client = $clientFactory->createClient(new Endpoint($config['url']));
        }

        return new static(
            $client,
            $config['client-id'],
            $config['secret']
        );
    }

    /**
     * @param $token
     */
    private static function assertValidToken($token)
    {
        if ('' === trim($token)) {
            throw new InvalidArgumentException('Token can not be empty.');
        }
    }

    /**
     * @var Client
     */
    private $client;

    /**
     * @var string Application client ID. Used by Oauth2Connector
     */
    private $clientId;

    /**
     * @var string Application secret. Used by Oauth2Connector
     */
    private $secret;

    /**
     * @var OAuth2Connector
     */
    private $connector;

    /**
     * @var EntityManager[]
     */
    private $entityManagers = array();

    /**
     * @var APIGuzzleAdapter[]
     */
    private $adapters = array();

    /**
     * @var Uploader[]
     */
    private $uploaders = array();

    /**
     * @var Monitor[]
     */
    private $monitors = array();

    /**
     * @param ApiClient $client
     * @param string $clientId
     * @param string $secret
     */
    public function __construct(ApiClient $client, $clientId, $secret)
    {
        $this->client = $client;
        $this->clientId = $clientId;
        $this->secret = $secret;
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
        if ($mode) {
            $this->client->enableExtendedMode();
        } else {
            $this->client->disableExtendedMode();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getOauth2Connector()
    {
        if ($this->connector === null) {
            $this->connector = new OAuth2Connector($this->client, $this->clientId, $this->secret);
        }

        return $this->connector;
    }

    /**
     * {@inheritdoc}
     */
    public function getUploader($token)
    {
        self::assertValidToken($token);

        if (!isset($this->uploaders[$token])) {
            $this->uploaders[$token] = new Uploader($this->getAdapterByToken($token), $this->getEntityManager($token));
        }

        return $this->uploaders[$token];
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityManager($token)
    {
        self::assertValidToken($token);

        if (!isset($this->entityManagers[$token])) {
            $this->entityManagers[$token] = new EntityManager(
                $this->getAdapterByToken($token),
                new NullLogger()
            );
        }

        return $this->entityManagers[$token];
    }

    /**
     * {@inheritdoc}
     */
    public function getMonitor($token)
    {
        self::assertValidToken($token);

        if (!isset($this->monitors[$token])) {
            $this->monitors[$token] = new Monitor($this->getAdapterByToken($token));
        }

        return $this->monitors[$token];
    }

    /**
     * Returns the guzzle adapter
     *
     * @return GuzzleClient
     */
    public function getAdapter()
    {
        return $this->client;
    }

    private function getAdapterByToken($token)
    {
        if (!isset($this->adapters[$token])) {
            $this->adapters[$token] = new APIGuzzleAdapter(
                new ConnectedGuzzleAdapter(
                    $token,
                    $this->client
                )
            );
        }

        return $this->adapters[$token];
    }
}
