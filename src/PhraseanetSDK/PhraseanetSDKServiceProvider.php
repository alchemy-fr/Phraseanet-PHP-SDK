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
use PhraseanetSDK\Client;
use PhraseanetSDK\Cache\RevalidationFactory;
use PhraseanetSDK\Cache\CanCacheStrategy;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Monolog\Handler\NullHandler;
use PhraseanetSDK\Profiler\PhraseanetSDKDataCollector;
use Guzzle\Plugin\History\HistoryPlugin;
use PhraseanetSDK\Recorder\Recorder;
use PhraseanetSDK\Recorder\Storage\StorageFactory;

/**
 * Phraseanet SDK Silex provider
 */
class PhraseanetSDKServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['phraseanet-sdk.config'] = $app['phraseanet-sdk.recorder.config'] = array();

        $app['phraseanet-sdk.cache.factory'] = $app->share(function (Application $app) {
            return new CacheFactory();
        });
        $app['phraseanet-sdk.guzzle.revalidation-factory'] = $app->share(function (Application $app) {
            return new RevalidationFactory();
        });
        $app['phraseanet-sdk.guzzle.can-cache-strategy'] = $app->share(function (Application $app) {
            return new CanCacheStrategy();
        });

        if (!isset($app['monolog.handler'])) {
            $app['monolog.handler'] = function () use ($app) {
                return new NullHandler();
            };
        }

        $app['phraseanet-sdk.guzzle.plugins'] = $app->share(function () {
            return array();
        });

        $app['phraseanet-sdk.cache.default'] =
        $app['phraseanet-sdk.cache'] = array(
            'type'       => 'array',
            'lifetime'   => 300,
            'revalidate' => 'skip',
        );

        $app['phraseanet-sdk'] = $app->share(function (Application $app) {
            $config = $app['phraseanet-sdk.config'] = array_replace_recursive(array(
                'cache'     => array(
                    'factory'    => $app['phraseanet-sdk.cache.factory'],
                ),
                'guzzle' => array(
                    'revalidation-factory' => $app['phraseanet-sdk.guzzle.revalidation-factory'],
                    'can-cache-strategy'   => $app['phraseanet-sdk.guzzle.can-cache-strategy'],
                    'plugins'              => $app['phraseanet-sdk.guzzle.plugins'],
                )
            ), $app['phraseanet-sdk.config']);

            return Client::create($config);
        });

        $app['phraseanet-sdk.recorder.enabled'] = false;

        $app['phraseanet-sdk.guzzle.history-plugin'] = $app->share(function (Application $app) {
            $plugin = new HistoryPlugin();
            $plugin->setLimit(9999);

            return $plugin;
        });

        $app['phraseanet-sdk.recorder.enabled'] = false;

        $app['phraseanet-sdk.guzzle.plugins'] = $app->share($app->extend('phraseanet-sdk.guzzle.plugins', function ($plugins, $app) {
            if (isset($app['profiler']) || $app['phraseanet-sdk.recorder.enabled']) {
                $plugins[] = $app['phraseanet-sdk.guzzle.history-plugin'];
            }

            return $plugins;
        }));

        if (isset($app['profiler'])) {
            $app['data_collectors']= array_merge($app['data_collectors'], array(
                'phraseanet-sdk' => $app->share(function ($app) {
                    return new PhraseanetSDKDataCollector($app['phraseanet-sdk.guzzle.history-plugin']);
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

        $app['phraseanet-sdk.recorder.storage-factory'] = $app->share(function (Application $app) {
            return new StorageFactory($app['phraseanet-sdk.cache.factory']);
        });

        $app['phraseanet-sdk.recorder'] = $app->share(function (Application $app) {
            $config = $app['phraseanet-sdk.recorder.config'] = array_replace_recursive(array(
                'type' => 'file',
                'options' => array(
                    'file' => realpath(__DIR__ . '/../..') . '/phraseanet.recorder.json',
                ),
                'limit' => 400,
            ), $app['phraseanet-sdk.recorder.config']);

            return new Recorder(
                $app['phraseanet-sdk.guzzle.history-plugin'],
                $app['phraseanet-sdk.recorder.storage-factory']->create($config['type'], $config['options']),
                $config['limit']
            );
        });

    }

    public function boot(Application $app)
    {
        if ($app['phraseanet-sdk.recorder.enabled']) {
            $app->finish(function () use ($app) {
                $app['phraseanet-sdk.recorder']->save();

                return;
            });
        }
    }
}
