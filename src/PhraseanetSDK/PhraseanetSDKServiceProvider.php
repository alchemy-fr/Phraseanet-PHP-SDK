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
use PhraseanetSDK\Profiler\PhraseanetSDKDataCollector;
use Guzzle\Plugin\History\HistoryPlugin;

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

        $app['phraseanet-sdk.guzzle-plugins'] = $app->share(function () {
            return array();
        });

        $app['phraseanet-sdk.config'] = $app->share(function (Application $app) {
            $config = array(
                'key'                        => $app['phraseanet-sdk.apiKey'],
                'secret'                     => $app['phraseanet-sdk.apiSecret'],
                'url'                        => $app['phraseanet-sdk.apiUrl'],
                'cache_revalidation_factory' => $app['phraseanet-sdk.cache-revalidation-factory'],
                'guzzle_can_cache'           => $app['phraseanet-sdk.cache-can-cache-strategy'],
                'plugins'                    => $app['phraseanet-sdk.guzzle-plugins'],
                'cache'                      => array(
                    'type'       => isset($app['phraseanet-sdk.cache']) ? $app['phraseanet-sdk.cache'] : 'array',
                    'host'       => isset($app['phraseanet-sdk.cache_host']) ? $app['phraseanet-sdk.cache_host'] : null,
                    'port'       => isset($app['phraseanet-sdk.cache_port']) ? $app['phraseanet-sdk.cache_port'] : null,
                    'lifetime'   => isset($app['phraseanet-sdk.cache_ttl']) ? $app['phraseanet-sdk.cache_ttl'] : 300,
                    'revalidate' => isset($app['phraseanet-sdk.cache_revalidate']) ? $app['phraseanet-sdk.cache_revalidate'] : 'skip',
                ),
            );

            if (isset($app['monolog'])) {
                $config['logger'] = $app['monolog'];
            }
            if (isset($app['phraseanet-sdk.apiDevToken'])) {
                $config['token'] = $app['phraseanet-sdk.apiDevToken'];
            }

            return $config;
        });

        $app['phraseanet-sdk'] = $app->share(function (Application $app) {
            return Client::create($app['phraseanet-sdk.config']);
        });

        if (isset($app['profiler'])) {
            $app['phraseanet-sdk.history-plugin'] = $app->share(function (Application $app) {
                return new HistoryPlugin();
            });

            $app['phraseanet-sdk.guzzle-plugins'] = $app->share($app->extend('phraseanet-sdk.guzzle-plugins', function ($plugins, $app) {
                $plugins[] = $app['phraseanet-sdk.history-plugin'];

                return $plugins;
            }));

            $app['data_collectors']= array_merge($app['data_collectors'], array(
                'phraseanet-sdk' => $app->share(function ($app) {
                    return new PhraseanetSDKDataCollector($app['phraseanet-sdk.history-plugin']);
                }),
            ));
            $app['data_collector.templates'] = array_merge($app['data_collector.templates'], array(
                array('phrasea-sdk', '@PhraseanetSDK/Collector/phraseanet-sdk.html.twig')
            ));

            $app['phraseanet-sdk.profiler.templates_path'] = __DIR__ . '/Profiler/resources/views';

            $app['twig.loader.filesystem'] = $app->share($app->extend('twig.loader.filesystem', function ($loader, $app) {
                $loader->addPath($app['phraseanet-sdk.profiler.templates_path'], 'PhraseanetSDK');

                return $loader;
            }));
        }
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
