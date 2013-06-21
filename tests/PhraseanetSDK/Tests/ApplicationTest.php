<?php

namespace PhraseanetSDK\Tests;

use PhraseanetSDK\Application;
use Guzzle\Plugin\History\HistoryPlugin;
use PhraseanetSDK\Exception\RuntimeException;
use Guzzle\Http\Message\Response;
use Guzzle\Plugin\Mock\MockPlugin;

class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideVariousConfigurations
     */
    public function testCreate($config, $cache, $plugins)
    {
        $application = Application::create($config, $cache, $plugins);

        $this->assertInstanceOf('PhraseanetSDK\Application', $application);
        $this->assertEquals('http://phraseanet.com/api/v1/', $application->getAdapter()->getGuzzle()->getBaseUrl());
    }

    public function provideVariousConfigurations()
    {
        $revalidation = $this->getMockBuilder('PhraseanetSDK\Cache\RevalidationFactory')
            ->disableOriginalConstructor()->getMock();
        $revalidation->expects($this->any())
            ->method('create')
            ->with('maybe')
            ->will($this->returnValue($this->getMock('Guzzle\Plugin\Cache\RevalidationInterface')));

        $cancache = $this->getMock('Guzzle\Plugin\Cache\CanCacheStrategyInterface');

        $cache = $this->getMockBuilder('Guzzle\Cache\DoctrineCacheAdapter')
            ->disableOriginalConstructor()->getMock();

        $factorySuccess = $this->getMock('PhraseanetSDK\Cache\CacheFactoryInterface');
        $factorySuccess->expects($this->once())
            ->method('createGuzzleCacheAdapter')
            ->will($this->returnValue($cache));

        $factoryFailure = $this->getMock('PhraseanetSDK\Cache\CacheFactoryInterface');
        $factoryFailure->expects($this->at(0))
            ->method('createGuzzleCacheAdapter')
            ->with('memcached', 'notlocalhost', 1234)
            ->will($this->throwException(new RuntimeException('Failure')));
        $factoryFailure->expects($this->at(1))
            ->method('createGuzzleCacheAdapter')
            ->with('array')
            ->will($this->returnValue($cache));

        $loggerThatLogsError = $this->getMock('Psr\Log\LoggerInterface');
        $loggerThatLogsError->expects($this->once())
            ->method('error');

        $loggerThatLogsDebug = $this->getMock('Psr\Log\LoggerInterface');
        $loggerThatLogsDebug->expects($this->once())
            ->method('debug');

        return array(
            array(
                array(
                    'client-id' => '12345',
                    'secret' => '54321',
                    'url' => 'http://phraseanet.com',
                ),
                array(),
                array(),
            ),
            array(
                array(
                    'client-id' => '12345',
                    'secret' => '54321',
                    'url' => 'http://phraseanet.com',
                    'logger' => $this->getMock('Psr\Log\LoggerInterface'),
                ),
                array(
                    'type'       => 'array',
                    'revalidate' => 'deny',
                ),
                array(
                    new HistoryPlugin()
                ),
            ),
            array(
                array(
                    'client-id' => '12345',
                    'secret' => '54321',
                    'url' => 'http://phraseanet.com/',
                ),
                array(),
                array(),
            ),
            array(
                array(
                    'client-id' => '12345',
                    'secret' => '54321',
                    'url' => 'http://phraseanet.com/api/v1/',
                ),
                array(),
                array(),
            ),
            array(
                array(
                    'client-id' => '12345',
                    'secret' => '54321',
                    'url' => 'http://phraseanet.com/api/v1',
                    'logger' => $loggerThatLogsDebug,
                ),
                array(
                    'revalidate'           => 'maybe',
                    'revalidation-factory' => $revalidation,
                    'can-cache-strategy'   => $cancache,
                    'factory'              => $factorySuccess,
                ),
                array(),
            ),
            array(
                array(
                    'client-id' => '12345',
                    'secret' => '54321',
                    'url' => 'http://phraseanet.com/api/v1',
                    'logger' => $loggerThatLogsError,
                ),
                array(
                    'type'                 => 'memcached',
                    'host'                 => 'notlocalhost',
                    'port'                 => 1234,
                    'revalidate'           => 'maybe',
                    'revalidation-factory' => $revalidation,
                    'can-cache-strategy'   => $cancache,
                    'factory'              => $factoryFailure,
                ),
                array(),
            ),
        );
    }

    /**
     * @dataProvider provideVariousInvalidConfigurations
     * @expectedException PhraseanetSDK\Exception\InvalidArgumentException
     */
    public function testCreateFailure($config)
    {
        Application::create($config);
    }

    public function provideVariousInvalidConfigurations()
    {
        return array(
            array(
                array(
                    'secret' => '54321',
                    'url' => 'http://phraseanet.com/api/v1',
                ),
            ),
            array(
                array(
                    'client-id' => '12345',
                    'url' => 'http://phraseanet.com/api/v1',
                ),
            ),
            array(
                array(
                    'client-id' => '12345',
                    'secret' => '54321',
                ),
            ),
        );
    }

    public function testOauth2ConnectorAlwaysTheSame()
    {
        $adapter = $this->getMockBuilder('PhraseanetSDK\Http\GuzzleAdapter')
            ->disableOriginalConstructor()
            ->getMock();

        $application = new Application($adapter, '12345', '54321');
        $connector = $application->getOauth2Connector();

        $this->assertInstanceOf('PhraseanetSDK\OAuth2Connector', $connector);

        $this->assertSame($connector, $application->getOauth2Connector());
    }

    public function testEntityManagersAlwaysTheSame()
    {
        $adapter = $this->getMockBuilder('PhraseanetSDK\Http\GuzzleAdapter')
            ->disableOriginalConstructor()
            ->getMock();

        $token1 = 'abcdef';
        $token2 = 'fedcba';

        $application = new Application($adapter, '12345', '54321');

        $em1 = $application->getEntityManager($token1);
        $em2 = $application->getEntityManager($token2);

        $this->assertInstanceOf('PhraseanetSDK\EntityManager', $em1);
        $this->assertInstanceOf('PhraseanetSDK\EntityManager', $em2);

        $this->assertNotSame($em2, $em1);

        $this->assertSame($em1, $application->getEntityManager($token1));
        $this->assertSame($em2, $application->getEntityManager($token2));
    }

    public function testLoadersAreAlwaysTheSame()
    {
        $adapter = $this->getMockBuilder('PhraseanetSDK\Http\GuzzleAdapter')
            ->disableOriginalConstructor()
            ->getMock();

        $token1 = 'abcdef';
        $token2 = 'fedcba';

        $application = new Application($adapter, '12345', '54321');

        $loader1 = $application->getLoader($token1);
        $loader2 = $application->getLoader($token2);

        $this->assertInstanceOf('PhraseanetSDK\Loader', $loader1);
        $this->assertInstanceOf('PhraseanetSDK\Loader', $loader2);

        $this->assertNotSame($loader2, $loader1);

        $this->assertSame($loader1, $application->getLoader($token1));
        $this->assertSame($loader2, $application->getLoader($token2));
    }

    public function testMonitorAlwaysTheSame()
    {
        $adapter = $this->getMockBuilder('PhraseanetSDK\Http\GuzzleAdapter')
            ->disableOriginalConstructor()
            ->getMock();

        $token1 = 'abcdef';
        $token2 = 'fedcba';

        $application = new Application($adapter, '12345', '54321');

        $mon1 = $application->getMonitor($token1);
        $mon2 = $application->getMonitor($token2);

        $this->assertInstanceOf('PhraseanetSDK\Monitor', $mon1);
        $this->assertInstanceOf('PhraseanetSDK\Monitor', $mon2);

        $this->assertNotSame($mon1, $mon2);

        $this->assertSame($mon1, $application->getMonitor($token1));
        $this->assertSame($mon2, $application->getMonitor($token2));
    }

    /**
     * @dataProvider provideInvalidTokens
     * @expectedException PhraseanetSDK\Exception\InvalidArgumentException
     */
    public function testEntityManagersWithoutTokenThrowsException($token)
    {
        $adapter = $this->getMockBuilder('PhraseanetSDK\Http\GuzzleAdapter')
            ->disableOriginalConstructor()
            ->getMock();

        $application = new Application($adapter, '12345', '54321');

        $application->getEntityManager($token);
    }

    public function provideInvalidTokens()
    {
        return array(
            array(null),
            array(''),
        );
    }

    public function testClientWithCacheDoNotExecuteQueries()
    {
        $stored = false;
        $cacheTTL = 666;
        $cacheType = 'memcached';
        $cacheHost = '127.0.0.1';
        $cachePort = 11211;

        $cache = $this->getMockBuilder('Guzzle\Cache\DoctrineCacheAdapter')
            ->disableOriginalConstructor()
            ->getMock();

        $phpunit = $this;
        $cache->expects($this->once())
            ->method('save')
            ->will($this->returnCallback(function ($id, $data, $ttl) use (&$stored, $cacheTTL, $phpunit) {
                $stored = $data;
                $phpunit->assertEquals($cacheTTL, $ttl);
            }));

        $cache->expects($this->exactly(3))
            ->method('fetch')
            ->will($this->returnCallback(function () use (&$stored) {
                return $stored;
            }));

        $factory = $this->getMock('PhraseanetSDK\Cache\CacheFactoryInterface');
        $factory->expects($this->once())
            ->method('createGuzzleCacheAdapter')
            ->with($cacheType, $cacheHost, $cachePort)
            ->will($this->returnValue($cache));

        $config = array(
            'client-id' => '49ce2762ff5413607ae936b2ca6e409e',
            'secret' => '0da92439654ac3e45f9bbba67f53ea9b',
            'token' => '6774eda355b03c1d6b671010d855d9f6',
            'url' => 'https://demo.alchemyasp.com/',
        );

        $cacheConfig = array(
            'type' => $cacheType,
            'host'=> $cacheHost,
            'port'=> $cachePort,
            'ttl' => $cacheTTL,
            'factory' => $factory,
        );

        $mock = new MockPlugin();
        $body = json_encode(array(
            'meta' => array(),
            'response' => array(),
        ));
        $mock->addResponse(new Response(200, null, $body));

        $app = Application::create($config, $cacheConfig, array($mock));

        $app->getAdapter()->getGuzzle()->addSubscriber($mock);

        $app->getAdapter()->call('GET', '/url/lala');
        $app->getAdapter()->call('GET', '/url/lala');
        $app->getAdapter()->call('GET', '/url/lala');
    }

    /**
     * @dataProvider provideCacheConfigurations
     */
    public function testACacheProviderIsAlwaysBuilt($type, $errors, $infos)
    {
        $logger = $this->getMock('Psr\Log\LoggerInterface');
        $logger->expects($this->exactly($errors))
            ->method('error');
        $logger->expects($this->exactly($infos))
            ->method('debug');

        Application::create(array(
            'client-id' => '1234',
            'secret' => '4321',
            'url' => 'http://www.example.com/',
            'logger' => $logger,
        ), array(
            'type' => $type,
            'host' => null,
            'port' => null,
        ));
    }

    public function provideCacheConfigurations()
    {
        return array(
            array('array', 0, 1),
            array('memcache', 0, 1),
            array('memcached', 0, 1),
            array('unknown', 1, 0),
        );
    }
}
