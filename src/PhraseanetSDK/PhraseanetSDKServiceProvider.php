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

use PhraseanetSDK\Cache\CacheFactory;
use PhraseanetSDK\Exception\RuntimeException;
use PhraseanetSDK\Client;
use PhraseanetSDK\Cache\RevalidationFactory;
use PhraseanetSDK\Cache\CanCacheStrategy;
use Silex\Application;
use Silex\ServiceProviderInterface;
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
        $app['phraseanet-sdk.cache-revalidation-factory'] = $app->share(function (Application $app) {
            return new RevalidationFactory();
        });
        $app['phraseanet-sdk.cache-can-cache-strategy'] = $app->share(function (Application $app) {
            return new CanCacheStrategy();
        });

        if (!isset($app['monolog.handler'])) {
            $app['monolog.handler'] = function () use ($app) {
                return new NullHandler();
            };
        }

        $app['phraseanet-sdk.guzzle-plugins'] = array();

        $app['phraseanet-sdk.config'] = $app->share(function (Application $app) {
            $config = array(
                'key'                        => $app['phraseanet-sdk.apiKey'],
                'secret'                     => $app['phraseanet-sdk.apiSecret'],
                'url'                        => $app['phraseanet-sdk.apiUrl'],
                'cache_revalidation_factory' => $app['phraseanet-sdk.cache-revalidation-factory'],
                'guzzle_can_cache'           => $app['phraseanet-sdk.cache-can-cache-strategy'],
                'logger'                     => $app['monolog'],
                'plugins'                    => $app['phraseanet-sdk.guzzle-plugins'],
                'cache'                      => array(
                    'type'       => isset($app['phraseanet-sdk.cache']) ? $app['phraseanet-sdk.cache'] : 'array',
                    'host'       => isset($app['phraseanet-sdk.cache_host']) ? $app['phraseanet-sdk.cache_host'] : null,
                    'port'       => isset($app['phraseanet-sdk.cache_port']) ? $app['phraseanet-sdk.cache_port'] : null,
                    'lifetime'   => isset($app['phraseanet-sdk.cache_ttl']) ? $app['phraseanet-sdk.cache_ttl'] : 300,
                    'revalidate' => isset($app['phraseanet-sdk.cache_revalidate']) ? $app['phraseanet-sdk.cache_revalidate'] : 'skip',
                ),
            );

            if (isset($app['phraseanet-sdk.apiDevToken'])) {
                $config['token'] = $app['phraseanet-sdk.apiDevToken'];
            }

            return $config;
        });

        $app['phraseanet-sdk'] = $app->share(function (Application $app) {
            return Client::create($app['phraseanet-sdk.config']);
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
