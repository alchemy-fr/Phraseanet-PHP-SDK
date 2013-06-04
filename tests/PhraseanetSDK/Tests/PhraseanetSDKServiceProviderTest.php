<?php

namespace PhraseanetSDK\Tests;

use PhraseanetSDK\PhraseanetSDKServiceProvider;
use Monolog\Handler\NullHandler;
use Silex\Application;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\WebProfilerServiceProvider;
use Silex\Provider\TwigServiceProvider;

class PhraseanetSDKServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideServices
     */
    public function testServices($name, $instanceOf)
    {
        $app = $this->getConfiguredApplication();
        $app->register(new PhraseanetSDKServiceProvider());

        $this->assertInstanceOf($instanceOf, $app[$name]);
    }

    public function provideServices()
    {
        return array(
            array('phraseanet-sdk', 'PhraseanetSDK\Client'),
            array('phraseanet-sdk.cache-factory', 'PhraseanetSDK\Cache\CacheFactory'),
            array('phraseanet-sdk.cache-revalidation-factory', 'PhraseanetSDK\Cache\RevalidationFactory'),
            array('phraseanet-sdk.cache-can-cache-strategy', 'PhraseanetSDK\Cache\CanCacheStrategy'),
        );
    }

    /**
     * @dataProvider provideServicesWithProfiler
     */
    public function testServicesWithProfiler($name, $instanceOf)
    {
        $app = $this->getConfiguredApplication();
        $app->register(new TwigServiceProvider());
        $app->register(new WebProfilerServiceProvider(), array(
            'profiler.cache_dir' => __DIR__ . '/cache',
        ));
        $app->register(new PhraseanetSDKServiceProvider());

        $this->assertInstanceOf($instanceOf, $app[$name]);
    }

    public function provideServicesWithProfiler()
    {
        return array(
            array('phraseanet-sdk.history-plugin', 'Guzzle\Plugin\History\HistoryPlugin'),
        );
    }

    private function getConfiguredApplication()
    {
        $app = new Application();

        $app['phraseanet-sdk.apiKey'] = 'sdfmqlsdkfm';
        $app['phraseanet-sdk.apiSecret'] = 'eoieep';
        $app['phraseanet-sdk.apiUrl'] = 'https://bidule.net';

        $app->register(new MonologServiceProvider());
        $app['monolog.handler'] = new NullHandler();

        return $app;
    }
}
