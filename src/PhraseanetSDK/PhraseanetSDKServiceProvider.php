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
use PhraseanetSDK\Http\ConnectedGuzzleAdapter;
use PhraseanetSDK\Http\APIGuzzleAdapter;
use Silex\Application as SilexApplication;
use Silex\ServiceProviderInterface;

/**
 * Phraseanet SDK Silex provider
 */
class PhraseanetSDKServiceProvider implements ServiceProviderInterface
{
    public function register(SilexApplication $app)
    {
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

        $app['phraseanet-sdk.guzzle.plugins'] = $app->share(function ($app) {
            return [];
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
    }

    public function boot(SilexApplication $app)
    {
    }
}
