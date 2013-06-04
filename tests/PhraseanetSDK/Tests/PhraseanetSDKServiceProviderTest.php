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
            array('phraseanet-sdk.cache-adapter', 'Guzzle\Cache\DoctrineCacheAdapter'),
            array('phraseanet-sdk.guzzle-client', 'Guzzle\Http\ClientInterface'),
        );
    }

    /**
     * @dataProvider provideCacheConfigurations
     */
    public function testACacheProviderIsAlwaysReturned($type, $instanceOf, $errors, $infos)
    {
        $app = $this->getConfiguredApplication();
        $app->register(new PhraseanetSDKServiceProvider());

        $app['monolog'] = $this->getMockBuilder('Monolog\Logger')
            ->disableOriginalConstructor()
            ->getMock();
        $app['monolog']->expects($this->exactly($errors))
            ->method('error');
        $app['monolog']->expects($this->exactly($infos))
            ->method('debug');

        $app['phraseanet-sdk.cache_host'] = null;
        $app['phraseanet-sdk.cache_port'] = null;
        $app['phraseanet-sdk.cache'] = $type;

        $this->assertInstanceOf($instanceOf, $app['phraseanet-sdk.cache-adapter']->getCacheObject());
    }

    public function provideCacheConfigurations()
    {
        return array(
            array('array', 'Doctrine\Common\Cache\ArrayCache', 0, 1),
            array('memcache', 'Doctrine\Common\Cache\MemcacheCache', 0, 1),
            array('memcached', 'Doctrine\Common\Cache\MemcachedCache', 0, 1),
            array('unknown', 'Doctrine\Common\Cache\ArrayCache', 1, 0),
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
