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

use Guzzle\Plugin\History\HistoryPlugin;
use PhraseanetSDK\Cache\CacheFactory;
use PhraseanetSDK\Cache\RevalidationFactory;
use PhraseanetSDK\Cache\CanCacheStrategy;
use PhraseanetSDK\Http\GuzzleAdapter;
use PhraseanetSDK\Http\ConnectedGuzzleAdapter;
use PhraseanetSDK\Http\APIGuzzleAdapter;
use PhraseanetSDK\Profiler\PhraseanetSDKDataCollector;
use PhraseanetSDK\Recorder\Recorder;
use PhraseanetSDK\Recorder\Player;
use PhraseanetSDK\Recorder\RequestExtractor;
use PhraseanetSDK\Recorder\Storage\StorageFactory;
use PhraseanetSDK\Recorder\Filters\MonitorFilter;
use PhraseanetSDK\Recorder\Filters\DuplicateFilter;
use PhraseanetSDK\Recorder\Filters\LimitFilter;
use Silex\Application as SilexApplication;
use Silex\ServiceProviderInterface;

/**
 * Phraseanet SDK Silex provider
 */
class PhraseanetSDKServiceProvider implements ServiceProviderInterface
{
    public function register(SilexApplication $app)
    {
        $app['phraseanet-sdk.recorder.config'] = array();
        $app['phraseanet-sdk.config'] = array();
        $app['phraseanet-sdk.cache.config'] = array();

        $app['phraseanet-sdk.recorder.config.merged'] = $app->share(function(SilexApplication $app) {
            return $app['phraseanet-sdk.recorder.config'] = array_replace_recursive(array(
                'type' => 'file',
                'options' => array(
                    'file' => realpath(__DIR__ . '/../..') . '/phraseanet.recorder.json',
                ),
                'limit' => 1000,
            ), $app['phraseanet-sdk.recorder.config']);
        });

        $app['phraseanet-sdk.cache.config.merged'] = $app->share(function(SilexApplication $app) {
            return $app['phraseanet-sdk.cache.config'] = array_replace_recursive(array(
                'type'       => 'array',
                'lifetime'   => 300,
                'revalidate' => 'skip',
                'factory'    => $app['phraseanet-sdk.cache.factory'],
                'revalidation-factory' => $app['phraseanet-sdk.guzzle.revalidation-factory'],
                'can-cache-strategy'   => $app['phraseanet-sdk.guzzle.can-cache-strategy'],
            ), $app['phraseanet-sdk.cache.config']);
        });

        $app['phraseanet-sdk.cache.factory'] = $app->share(function (SilexApplication $app) {
            return new CacheFactory();
        });
        $app['phraseanet-sdk.guzzle.revalidation-factory'] = $app->share(function (SilexApplication $app) {
            return new RevalidationFactory();
        });
        $app['phraseanet-sdk.guzzle.can-cache-strategy'] = $app->share(function (SilexApplication $app) {
            return new CanCacheStrategy();
        });

        $app['phraseanet-sdk.guzzle.plugins'] = $app->share(function () {
            return array();
        });

        $app['phraseanet-sdk.guzzle-adapter'] = $app->share(function (SilexApplication $app) {
            return GuzzleAdapter::create(
                $app['phraseanet-sdk.config'],
                $app['phraseanet-sdk.cache.config.merged'],
                $app['phraseanet-sdk.guzzle.plugins']
            );
        });

        $app['phraseanet-sdk.guzzle-connected-adapter'] = $app->protect(function ($token) use ($app) {
            return new ConnectedGuzzleAdapter($token, $app['phraseanet-sdk.guzzle-adapter']);
        });

        $app['phraseanet-sdk.guzzle-api-adapter'] = $app->protect(function ($token) use ($app) {
            return new APIGuzzleAdapter($app['phraseanet-sdk.guzzle-connected-adapter']($token));
        });

        $app['phraseanet-sdk'] = $app->share(function (SilexApplication $app) {
            return new Application(
                $app['phraseanet-sdk.guzzle-adapter'],
                $app['phraseanet-sdk.config']['client-id'],
                $app['phraseanet-sdk.config']['secret']
            );
        });

        $app['phraseanet-sdk.guzzle.history-plugin'] = $app->share(function (SilexApplication $app) {
            $plugin = new HistoryPlugin();
            $plugin->setLimit($app['phraseanet-sdk.recorder.config.merged']['limit']);

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

        $app['phraseanet-sdk.recorder.storage-factory'] = $app->share(function (SilexApplication $app) {
            return new StorageFactory($app['phraseanet-sdk.cache.factory']);
        });

        $app['phraseanet-sdk.recorder.request-extractor'] = $app->share(function (SilexApplication $app) {
            return new RequestExtractor();
        });

        $app['phraseanet-sdk.recorder.storage'] = $app->share(function (SilexApplication $app) {
            $config = $app['phraseanet-sdk.recorder.config.merged'];

            return $app['phraseanet-sdk.recorder.storage-factory']->create($config['type'], $config['options']);
        });

        $app['phraseanet-sdk.recorder.filters'] = $app->share(function (SilexApplication $app) {
            return array(
                new MonitorFilter(),
                new DuplicateFilter(),
                new LimitFilter($app['phraseanet-sdk.recorder.config.merged']['limit']),
            );
        });

        $app['phraseanet-sdk.recorder'] = $app->share(function (SilexApplication $app) {
            $recorder = new Recorder(
                $app['phraseanet-sdk.guzzle.history-plugin'],
                $app['phraseanet-sdk.recorder.storage'],
                $app['phraseanet-sdk.recorder.request-extractor']
            );

            foreach ($app['phraseanet-sdk.recorder.filters'] as $filter) {
                $recorder->addFilter($filter);
            }

            return $recorder;
        });

        $app['phraseanet-sdk.player.factory'] = $app->protect(function ($token) use ($app) {
            return new Player(
                $app['phraseanet-sdk.guzzle-api-adapter']($token),
                $app['phraseanet-sdk.recorder.storage']
            );
        });
    }

    public function boot(SilexApplication $app)
    {
        if ($app['phraseanet-sdk.recorder.enabled']) {
            $app->finish(function () use ($app) {
                $app['phraseanet-sdk.recorder']->save();

                return;
            });
        }
    }
}
