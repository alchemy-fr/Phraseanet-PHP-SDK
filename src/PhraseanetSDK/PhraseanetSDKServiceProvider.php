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

use Guzzle\Cache\DoctrineCacheAdapter;
use Guzzle\Plugin\Cache\CachePlugin;
use Guzzle\Plugin\History\HistoryPlugin;
use PhraseanetSDK\Cache\BackendCacheFactory;
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
        $app['recorder.config'] = $app['sdk.config'] = $app['cache.config'] = array();

        $app['phraseanet-sdk.recorder.config'] = $app->share(function () use ($app) {
            return array_replace_recursive(
                array(
                    'type' => 'file',
                    'options' => array(
                        'file' => realpath(__DIR__.'/../..').'/phraseanet.recorder.json',
                    ),
                    'limit' => 1000,
                ),
                $app['recorder.config']
            );
        });

        $app['phraseanet-sdk.config'] = $app->share(function () use ($app) {
            return array_replace(
                array(
                    'client-id' => null,
                    'secret'    => null,
                    'url'       => null,
                ),
                $app['sdk.config']
            );
        });

        $app['phraseanet-sdk.cache.config'] = $app->share(function () use ($app) {
            return array_replace(
                array(
                    'type' => 'array',
                    'ttl' => 300,
                ),
                $app['cache.config']
            );
        });

        $app['phraseanet-sdk.cache.factory'] = $app->share(function () use ($app) {
            return new BackendCacheFactory();
        });

        $app['phraseanet-sdk.cache.adapter'] = $app->share(function () use ($app) {
            $config = $app['phraseanet-sdk.cache.config'];

            $backend = $app['phraseanet-sdk.cache.factory']->create(
                $config['type'],
                isset($config['options']['host']) ? $config['options']['host'] : null,
                isset($config['options']['port']) ? $config['options']['port'] : null
            );

            return new DoctrineCacheAdapter($backend);
        });

        $app['phraseanet-sdk.cache.revalidation'] = $app->share(function (SilexApplication $app) {
            $config = $app['phraseanet-sdk.cache.config'];
            if (isset($config['revalidation']) && is_string($config['revalidation'])) {
                $factory = new RevalidationFactory();

                return $factory->create($config['revalidation']);
            } elseif (isset($config['revalidation'])) {
                return $config['revalidation'];
            }

            return;
        });

        $app['phraseanet-sdk.cache.can_cache'] = $app->share(function (SilexApplication $app) {
            $config = $app['phraseanet-sdk.cache.config'];
            if (isset($config['can_cache'])) {
                return $config['can_cache'];
            }

            return new CanCacheStrategy();
        });

        $app['phraseanet-sdk.cache.key_provider'] = $app->share(function (SilexApplication $app) {
            $config = $app['phraseanet-sdk.cache.config'];

            return isset($config['key_provider']) ? $config['key_provider'] : null;
        });

        $app['phraseanet-sdk.cache.plugin'] = $app->share(function () use ($app) {
            $cacheConfig = array_merge(
                $app['phraseanet-sdk.cache.config'],
                array(
                    'adapter' => $app['phraseanet-sdk.cache.adapter'],
                    'default_ttl' => $app['phraseanet-sdk.cache.config']['ttl'],
                    'key_provider' => $app['phraseanet-sdk.cache.key_provider'],
                    'can_cache' => $app['phraseanet-sdk.cache.can_cache'],
                    'revalidation' => $app['phraseanet-sdk.cache.revalidation'],
                )
            );

            return new CachePlugin($cacheConfig);
        });

        $app['phraseanet-sdk.guzzle.plugins'] = $app->share(function ($app) {
            $plugins = array(
                $app['phraseanet-sdk.cache.plugin'],
            );

            if (isset($app['profiler']) || $app['recorder.enabled']) {
                $plugins[] = $app['phraseanet-sdk.guzzle.history-plugin'];
            }

            return $plugins;
        });

        $app['phraseanet-sdk.guzzle-adapter'] = $app->share(function (SilexApplication $app) {
            return GuzzleAdapter::create(
                $app['phraseanet-sdk.config']['url'],
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
            return Application::create($app['phraseanet-sdk.config'], $app['phraseanet-sdk.guzzle-adapter']);
        });

        $app['phraseanet-sdk.guzzle.history-plugin'] = $app->share(function (SilexApplication $app) {
            $plugin = new HistoryPlugin();
            $plugin->setLimit($app['phraseanet-sdk.recorder.config']['limit']);

            return $plugin;
        });

        $app['recorder.enabled'] = false;

        if (isset($app['profiler'])) {
            $app['data_collectors'] = array_merge($app['data_collectors'], array(
                'phraseanet-sdk' => $app->share(function ($app) {
                    return new PhraseanetSDKDataCollector($app['phraseanet-sdk.guzzle.history-plugin']);
                }),
            ));
            $app['data_collector.templates'] = array_merge($app['data_collector.templates'], array(
                array('phrasea-sdk', '@PhraseanetSDK/Collector/phraseanet-sdk.html.twig'),
            ));

            $app['phraseanet-sdk.profiler.templates_path'] = __DIR__.'/Profiler/resources/views';

            $app['twig.loader.filesystem'] = $app->share($app->extend(
                'twig.loader.filesystem',
                function ($loader, $app) {
                    $loader->addPath($app['phraseanet-sdk.profiler.templates_path'], 'PhraseanetSDK');

                    return $loader;
                }
            ));
        }

        $app['phraseanet-sdk.recorder.storage-factory'] = $app->share(function (SilexApplication $app) {
            return new StorageFactory($app['phraseanet-sdk.cache.factory']);
        });

        $app['phraseanet-sdk.recorder.request-extractor'] = $app->share(function (SilexApplication $app) {
            return new RequestExtractor();
        });

        $app['phraseanet-sdk.recorder.storage'] = $app->share(function (SilexApplication $app) {
            $config = $app['phraseanet-sdk.recorder.config'];

            return $app['phraseanet-sdk.recorder.storage-factory']->create($config['type'], $config['options']);
        });

        $app['phraseanet-sdk.recorder.filters'] = $app->share(function (SilexApplication $app) {
            return array(
                new MonitorFilter(),
                new DuplicateFilter(),
                new LimitFilter($app['phraseanet-sdk.recorder.config']['limit']),
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
        if ($app['recorder.enabled']) {
            $app->finish(function () use ($app) {
                $app['phraseanet-sdk.recorder']->save();
            });
        }
    }
}
