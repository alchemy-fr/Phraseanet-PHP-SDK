<?php

/*
 * This file is part of Phraseanet SDK.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhraseanetSDK\Http;

use Guzzle\Common\Exception\GuzzleException;
use Guzzle\Http\Client as Guzzle;
use Guzzle\Http\ClientInterface;
use Guzzle\Http\Exception\BadResponseException as GuzzleBadResponse;
use Guzzle\Http\Exception\CurlException;
use Guzzle\Http\Message\EntityEnclosingRequestInterface;
use Guzzle\Http\Message\RequestInterface;
use Guzzle\Log\PsrLogAdapter;
use Guzzle\Plugin\Log\LogPlugin;
use Guzzle\Plugin\Cache\CachePlugin;
use PhraseanetSDK\ApplicationInterface;
use PhraseanetSDK\Exception\BadResponseException;
use PhraseanetSDK\Exception\InvalidArgumentException;
use PhraseanetSDK\Exception\RuntimeException;
use PhraseanetSDK\Cache\CacheFactory;
use PhraseanetSDK\Cache\RevalidationFactory;
use PhraseanetSDK\Cache\CanCacheStrategy;
use Psr\Log\LoggerInterface;

class GuzzleAdapter implements GuzzleAdapterInterface
{
    /** @var ClientInterface */
    private $guzzle;

    public function __construct(ClientInterface $guzzle)
    {
        $this->guzzle = $guzzle;
    }

    /**
     * {@inheritdoc}
     *
     * @return ClientInterface
     */
    public function getGuzzle()
    {
        return $this->guzzle;
    }

    /**
     * Returns the client base URL
     *
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->guzzle->getBaseUrl();
    }

    /**
     * Sets the user agent
     *
     * @param string $userAgent
     */
    public function setUserAgent($userAgent)
    {
        $this->guzzle->setUserAgent($userAgent);
    }

    /**
     * Performs an HTTP request, returns the body response
     *
     * @param string $method     The method
     * @param string $path       The path to query
     * @param array  $query      An array of query parameters
     * @param array  $postFields An array of post fields
     *
     * @return string The response body
     *
     * @throws BadResponseException
     * @throws RuntimeException
     */
    public function call($method, $path, array $query = array(), array $postFields = array())
    {
        try {
            $request = $this->guzzle->createRequest($method, $path, array('accept' => 'application/json'));
            $this->addRequestParameters($request, $query, $postFields);
            $response = $request->send();
        } catch (CurlException $e) {
            throw new RuntimeException($e->getMessage(), $e->getErrorNo(), $e);
        } catch (GuzzleBadResponse $e) {
            throw BadResponseException::fromGuzzleResponse($e);
        } catch (GuzzleException $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }

        return $response->getBody(true);
    }

    public static function create(array $config, array $cache = array(), array $plugins = array())
    {
        $config = static::getConfig($config);
        $cache = static::getCacheConfig($cache);

        $guzzle = new Guzzle(static::generateUrl($config['url']));
        $guzzle->setUserAgent(sprintf('Phraseanet SDK version %s', ApplicationInterface::VERSION));

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

        return new static($guzzle);
    }

    private function addRequestParameters(RequestInterface $request, $query, $postFields)
    {
        foreach ($query as $name => $value) {
            $request->getQuery()->add($name, $value);
        }

        if ($request instanceof EntityEnclosingRequestInterface) {
            foreach ($postFields as $name => $value) {
                $request->getPostFields()->add($name, $value);
            }
        } elseif (0 < count($postFields)) {
            throw new InvalidArgumentException('Can not add post fields to GET request');
        }
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

        return rtrim($url, '/') . ApplicationInterface::API_MOUNT_POINT;
    }
}
