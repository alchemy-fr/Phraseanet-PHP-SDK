<?php

/*
 * This file is part of Phraseanet SDK Silex Provider.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhraseanetSDK;

use PhraseanetSDK\Cache\CacheFactory;
//use Alchemy\Phrasea\SDK\Cache\CachePlugin;
use PhraseanetSDK\Exception\RuntimeException;
use Guzzle\Cache\DoctrineCacheAdapter;
use Guzzle\Http\Client as Guzzle;
use Guzzle\Plugin\Cache\CachePlugin;
use PhraseanetSDK\Client;
use PhraseanetSDK\HttpAdapter\Guzzle as GuzzleAdapter;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Guzzle\Plugin\Cache\SkipRevalidation;
use Guzzle\Plugin\Log\LogPlugin;
use Guzzle\Log\PsrLogAdapter;
use Monolog\Handler\NullHandler;

/**
 * Phraseanet SDK Silex provider
 */
class PhraseanetSDKServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['phraseanet-sdk.cache-factory'] = $app->share(function (Application $app) {
            return new CacheFactory();
        });

        if (!isset($app['monolog.handler'])) {
            $app['monolog.handler'] = function () use ($app) {
                return new NullHandler();
            };
        }

        $app['phraseanet-sdk.cache-adapter'] = $app->share(function (Application $app) {
            $host = isset($app['phraseanet-sdk.cache_host']) ? $app['phraseanet-sdk.cache_host'] : null;
            $port = isset($app['phraseanet-sdk.cache_port']) ? $app['phraseanet-sdk.cache_port'] : null;
            $type = isset($app['phraseanet-sdk.cache']) ? $app['phraseanet-sdk.cache'] : 'array';

            try {
                $cache = $app['phraseanet-sdk.cache-factory']->create($type, $host, $port);
                $app['monolog']->debug(sprintf('Phraseanet SDK using cache %s %s', $type, (($host||$port) ? ' with parameters '.$host.':'.$port : '')));
            } catch (RuntimeException $e) {
                $app['monolog']->error(sprintf('Error while instancing cache : %s', $e->getMessage()));

                $cache = $app['phraseanet-sdk.cache-factory']->create('array', null, null);
            }

            return new DoctrineCacheAdapter($cache);
        });



        $app['phraseanet-sdk.guzzle-client'] = $app->share(function (Application $app) {
            $guzzle = new Guzzle($app['phraseanet-sdk.apiUrl']);

            $lifetime = isset($app['phraseanet-sdk.cache_ttl']) ? $app['phraseanet-sdk.cache_ttl'] : 360;

            // skip or never
            $revalidate = isset($app['phraseanet-sdk.cache_revalidate']) ? $app['phraseanet-sdk.cache_revalidate'] : null;

            $guzzle->addSubscriber(new CachePlugin(array(
                'adapter'      => $app['phraseanet-sdk.cache-adapter'],
                'default_ttl'  => $lifetime,
                'revalidation' => new SkipRevalidation(),
            )));
            $guzzle->addSubscriber(new LogPlugin(new PsrLogAdapter($app['monolog'])));

            return $guzzle;
        });

        $app['phraseanet-sdk'] = $app->share(function() use ($app) {
            $client = new Client($app['phraseanet-sdk.apiKey'], $app['phraseanet-sdk.apiSecret'], new GuzzleAdapter($app['phraseanet-sdk.guzzle-client']), $app['monolog']);

            if (isset($app['phraseanet-sdk.apiDevToken'])) {
                $client->setAccessToken($app['phraseanet-sdk.apiDevToken']);
            }

            return $client;
        });
    }

    public function boot(Application $app)
    {
        if (!isset($app['phraseanet-sdk.apiSecret'])) {
            throw new RuntimeException('You must provide an api secret');
        }
        if (!isset($app['phraseanet-sdk.apiKey'])) {
            throw new RuntimeException('You must provide an api key');
        }
        if (!isset($app['phraseanet-sdk.apiUrl'])) {
            throw new RuntimeException('You must provide an api url');
        }
        if (!isset($app['monolog'])) {
            throw new RuntimeException('Phraseanet SDK Provider requires monolog service');
        }
    }
}
