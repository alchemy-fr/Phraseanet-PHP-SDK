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

use Guzzle\Plugin\Log\LogPlugin;
use Guzzle\Log\PsrLogAdapter;
use Guzzle\Http\Client as Guzzle;
use Guzzle\Plugin\Cache\CachePlugin;
use PhraseanetSDK\Http\GuzzleAdapter;
use PhraseanetSDK\Cache\CacheFactory;
use PhraseanetSDK\Cache\RevalidationFactory;
use PhraseanetSDK\Cache\CanCacheStrategy;
use PhraseanetSDK\Exception\RuntimeException;
use PhraseanetSDK\Exception\InvalidArgumentException;
use PhraseanetSDK\Http\ConnectedGuzzleAdapter;
use PhraseanetSDK\Http\APIGuzzleAdapter;
use Psr\Log\LoggerInterface;

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
    public function getEntityManager($token)
    {
        if ('' === trim($token)) {
            throw new InvalidArgumentException('Token can not be empty.');
        }

        if (isset($this->ems[$token])) {
            return $this->ems[$token];
        }

        return $this->ems[$token] = new EntityManager(
            new APIGuzzleAdapter(
                new ConnectedGuzzleAdapter(
                    $token, $this->adapter
                )
            )
        );
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
     *
     * @return Application
     *
     * @throws InvalidArgumentException In case a parameter is missing
     */
    public static function create(array $config, array $cache = array(), array $plugins = array())
    {
        $config = static::getConfig($config);
        $cache = static::getCacheConfig($cache);

        $guzzle = new Guzzle(static::generateUrl($config['url']));
        $guzzle->setUserAgent(sprintf('Phraseanet SDK version %s', static::VERSION));

        if (null !== $config['logger']) {
            $guzzle->addSubscriber(new LogPlugin(new PsrLogAdapter($config['logger'])));
        }

        $guzzle->addSubscriber(new CachePlugin(array(
            'adapter'      => static::createCacheAdapter($cache, $config['logger']),
            'can_cache'    => $cache['can-cache-strategy'],
            'default_ttl'  => $cache['ttl'],
            'revalidation' => $cache['revalidation-factory']->create($cache['revalidate']),
        )));

        foreach ($plugins as $plugin) {
            $guzzle->addSubscriber($plugin);
        }

        return new Application(new GuzzleAdapter($guzzle), $config['client-id'], $config['secret']);
    }

    private static function createCacheAdapter($cache, LoggerInterface $logger = null)
    {
        try {
            $cacheAdapter = $cache['factory']->createGuzzleCacheAdapter($cache['type'], $cache['host'], $cache['port']);
            if (isset($logger)) {
                $logger->debug(sprintf('Using cache adapter %s', $cache['type']));
            }
        } catch (RuntimeException $e) {
            if (isset($logger)) {
                $logger->error(sprintf('Unable to create cache adapter %s', $cache['type']));
            }
            $cacheAdapter = $cache['factory']->createGuzzleCacheAdapter('array');
        }

        return $cacheAdapter;
    }

    private static function getConfig(array $config)
    {
        $config = array_replace(array(
            'client-id' => null,
            'secret'    => null,
            'url'       => null,
            'logger'    => null,
        ), $config);

        foreach (array('client-id', 'secret', 'url') as $key) {
            if (null === $config[$key]) {
                throw new InvalidArgumentException(sprintf('Missing parameter %s', $key));
            }
        }

        return $config;
    }

    private static function getCacheConfig(array $cache)
    {
        $cache = array_replace(array(
            'type' => 'array',
            'host' => null,
            'port' => null,
            'ttl'  => 300,
            'revalidate' => 'skip',
        ), $cache);

        if (!isset($cache['factory'])) {
            $cache['factory'] = new CacheFactory();
        }

        if (!isset($cache['revalidation-factory'])) {
            $cache['revalidation-factory'] = new RevalidationFactory();
        }
        if (!isset($cache['can-cache-strategy'])) {
            $cache['can-cache-strategy'] = new CanCacheStrategy();
        }

        return $cache;
    }

    private static function generateUrl($url)
    {
        $end = substr($url, -7);

        if ('api/v1/' === $end) {
            return $url;
        }
        if ('/api/v1' === $end) {
            return $url . '/';
        }

        return rtrim($url, '/') . static::API_MOUNT_POINT;
    }
}
