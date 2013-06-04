<?php

namespace PhraseanetSDK\Tests;

use PhraseanetSDK\PhraseanetSDKServiceProvider;
use Monolog\Handler\NullHandler;
use Silex\Application;
use Silex\Provider\MonologServiceProvider;

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
