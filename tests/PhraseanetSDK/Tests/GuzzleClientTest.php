<?php

namespace PhraseanetSDK\Tests;

use Guzzle\Http\Client as Guzzle;
use Guzzle\Plugin\Mock\MockPlugin;
use Guzzle\Http\Message\Response;
use PhraseanetSDK\Client;
use PhraseanetSDK\HttpAdapter\Guzzle as Adapter;

class GuzzleClientTest extends AbstractClient
{

    public function getAdapter($response = null, $code = 200)
    {
        $plugin = new MockPlugin();

        $plugin->addResponse(new Response($code, null, $response));

        $clientHttp = $this->getClient();

        $clientHttp->getEventDispatcher()->addSubscriber($plugin);

        return new Adapter($clientHttp);
    }

    private function getClient()
    {
        return new Guzzle('http://my.domain.tld/', array('version' => 1));
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

        $configuration = array(
            'client-id' => '49ce2762ff5413607ae936b2ca6e409e',
            'secret' => '0da92439654ac3e45f9bbba67f53ea9b',
            'token' => '6774eda355b03c1d6b671010d855d9f6',
            'url' => 'https://demo.alchemyasp.com/',
            'cache' => array(
                'type' => $cacheType,
                'host'=> $cacheHost,
                'port'=> $cachePort,
                'ttl' => $cacheTTL,
                'factory' => $factory,
            ),
        );

        $client = Client::create($configuration);

        $mock = new MockPlugin();
        $body = json_encode(array(
            'meta' => array(),
            'response' => array(),
        ));
        $mock->addResponse(new Response(200, null, $body));

        $client->getHttpClient()->getAdapter()->addSubscriber($mock);

        $client->call('/url/lala', array());
        $client->call('/url/lala', array());
        $client->call('/url/lala', array());
    }

    /**
     * @dataProvider provideCacheConfigurations
     */
    public function testACacheProviderIsAlwaysBuilt($type, $errors, $infos)
    {
        $logger = $this->getMockBuilder('Monolog\Logger')
            ->disableOriginalConstructor()
            ->getMock();
        $logger->expects($this->exactly($errors))
            ->method('error');
        $logger->expects($this->exactly($infos))
            ->method('debug');

        Client::create(array(
            'client-id' => '1234',
            'secret' => '4321',
            'url' => 'http://www.example.com/',
            'cache' => array(
                'type' => $type,
                'host' => null,
                'port' => null,
            ),
            'logger' => $logger,
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
