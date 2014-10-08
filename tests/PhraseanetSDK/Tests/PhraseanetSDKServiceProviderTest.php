<?php

namespace PhraseanetSDK\Tests;

use PhraseanetSDK\PhraseanetSDKServiceProvider;
use Silex\Application;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\WebProfilerServiceProvider;
use Silex\Provider\TwigServiceProvider;
use PhraseanetSDK\Cache\CanCacheStrategy;

class PhraseanetSDKServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideServices
     */
    public function testServices($name, $instanceOf)
    {
        $app = $this->getConfiguredApplication();
        $app->register(new PhraseanetSDKServiceProvider(), array(
            'phraseanet-sdk.config' => array(
                'client-id' => '9dPT7Gq5',
                'secret'    => 'nEXqhaF5',
                'url'       => 'https://www.phraseanet.com',
            )
        ));
        $app->boot();

        $service = $app[$name];

        $this->assertInstanceOf($instanceOf, $service);
    }

    public function provideServices()
    {
        return array(
            array('phraseanet-sdk', 'PhraseanetSDK\Application'),
            array('phraseanet-sdk.guzzle-adapter', 'PhraseanetSDK\Http\GuzzleAdapter'),
            array('phraseanet-sdk.cache.adapter', 'Guzzle\Cache\CacheAdapterInterface'),
            array('phraseanet-sdk.cache.plugin', 'Guzzle\Plugin\Cache\CachePlugin'),
            array('phraseanet-sdk.guzzle.history-plugin', 'Guzzle\Plugin\History\HistoryPlugin'),
            array('phraseanet-sdk.recorder', 'PhraseanetSDK\Recorder\Recorder'),
        );
    }

    public function testPlayerFactory()
    {
        $app = $this->getConfiguredApplication();
        $app->register(new PhraseanetSDKServiceProvider(), array(
            'phraseanet-sdk.config' => array(
                'client-id' => 'eY+dnmxX',
                'secret'    => 'ru3wqcys',
                'url'       => 'https://bidule.net',
            )
        ));
        $app->boot();

        $player1 = $app['phraseanet-sdk.player.factory']('token');
        $player2 = $app['phraseanet-sdk.player.factory']('token');
        $this->assertInstanceOf('PhraseanetSDK\Recorder\Player', $player1);
        $this->assertInstanceOf('PhraseanetSDK\Recorder\Player', $player2);
        $this->assertNotSame($player2, $player1);
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
        $app->boot();

        $service = $app[$name];

        $this->assertInstanceOf($instanceOf, $service);
    }

    public function testHistoryPluginLoadedIfRecorderEnabled()
    {
        $app = $this->getConfiguredApplication();
        $app->register(new PhraseanetSDKServiceProvider());
        $app['recorder.enabled'] = true;

        $app->boot();

        $plugins = $app['phraseanet-sdk.guzzle.plugins'];
        $this->assertContains($app['phraseanet-sdk.guzzle.history-plugin'], $plugins);
    }

    public function testHistoryPluginLoadedIfProfilerEnabled()
    {
        $app = $this->getConfiguredApplication();
        $app->register(new TwigServiceProvider());
        $app->register(new WebProfilerServiceProvider(), array(
            'profiler.cache_dir' => __DIR__ . '/cache',
        ));
        $app->register(new PhraseanetSDKServiceProvider());

        $app->boot();

        $plugins = $app['phraseanet-sdk.guzzle.plugins'];
        $this->assertContains($app['phraseanet-sdk.guzzle.history-plugin'], $plugins);
    }

    public function provideServicesWithProfiler()
    {
        return array(
            array('phraseanet-sdk.guzzle.history-plugin', 'Guzzle\Plugin\History\HistoryPlugin'),
        );
    }

    public function testRecorderIsTriggeredOnFinishIfEnabled()
    {
        $app = $this->getConfiguredApplication();
        $app->register(new PhraseanetSDKServiceProvider(), array(
            'phraseanet-sdk.config' => array(
                'client-id' => 'sdfmqlsdkfm',
                'secret'    => 'eoieep',
                'url'       => 'https://bidule.net',
            )
        ));

        $app['recorder.enabled'] = true;
        $app['phraseanet-sdk.recorder'] = $this->getMockBuilder('PhraseanetSDK\Recorder\Recorder')
            ->disableOriginalConstructor()
            ->getMock();
        $app['phraseanet-sdk.recorder']->expects($this->once())
            ->method('save');

        $app->boot();

        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
            ->disableOriginalConstructor()
            ->getMock();

        $response = $this->getMockBuilder('Symfony\Component\HttpFoundation\Response')
            ->disableOriginalConstructor()
            ->getMock();

        $app->terminate($request, $response);
    }

    /**
     * @dataProvider provideVariousCacheConfigs
     */
    public function testCacheConfigMerge($config, $expected)
    {
        $app = $this->getConfiguredApplication();
        $app->register(new PhraseanetSDKServiceProvider(), array(
            'cache.config' => $config
        ));

        $revalidation = $app['phraseanet-sdk.cache.revalidation'];
        $canCache = $app['phraseanet-sdk.cache.can_cache'];
        $keyProvider = $app['phraseanet-sdk.cache.key_provider'];

        if (is_object($revalidation)) {
            $this->assertInstanceOf($expected['revalidation'], $revalidation);
        } else {
            $this->assertNull($expected['revalidation'], $app['phraseanet-sdk.cache.revalidation']);
        }
        if (is_object($canCache)) {
            $this->assertInstanceOf($expected['can_cache'], $canCache);
        } else {
            $this->assertNull($expected['can_cache'], $app['phraseanet-sdk.cache.can_cache']);
        }
        if (is_object($keyProvider)) {
            $this->assertInstanceOf($expected['key_provider'], $keyProvider);
        } else {
            $this->assertNull($expected['key_provider'], $app['phraseanet-sdk.cache.key_provider']);
        }
    }

    public function provideVariousCacheConfigs()
    {
        return array(
            array(
                array(
                    'type' => 'array',
                    'revalidation' => 'skip',
                ),
                array (
                    'revalidation' => 'Guzzle\Plugin\Cache\SkipRevalidation',
                    'can_cache' => 'PhraseanetSDK\Cache\CanCacheStrategy',
                    'key_provider' => null,
                )
            ),
            array(
                array(
                    'type' => 'redis',
                    'ttl' => 666,
                    'revalidation' => 'deny',
                ),
                array (
                    'revalidation' => 'Guzzle\Plugin\Cache\DenyRevalidation',
                    'can_cache' => 'PhraseanetSDK\Cache\CanCacheStrategy',
                    'key_provider' => null,
                )
            ),
            array(
                array(
                    'ttl' => 666,
                ),
                array (
                    'revalidation' => null,
                    'can_cache' => 'PhraseanetSDK\Cache\CanCacheStrategy',
                    'key_provider' => null,
                )
            ),
        );
    }

    /**
     * @dataProvider provideVariousRecorderConfigs
     */
    public function testRecorderConfigMerge($config, $expected)
    {
        $app = $this->getConfiguredApplication();
        $app->register(new PhraseanetSDKServiceProvider(), array(
            'recorder.config' => $config
        ));

        $this->assertEquals($expected, $app['phraseanet-sdk.recorder.config']);
    }

    public function provideVariousRecorderConfigs()
    {
        return array(
            array(
                array(
                ),
                array(
                    'type' => 'file',
                    'options' => array(
                        'file' => realpath(__DIR__ . '/../../..') . '/phraseanet.recorder.json',
                    ),
                    'limit' => 1000,
                )
            ),
            array(
                array(
                    'type' => 'memcached',
                    'options' => array(
                        'host' => '127.0.0.1'
                    ),
                    'limit' => 500,
                ),
                array(
                    'type' => 'memcached',
                    'options' => array(
                        'host' => '127.0.0.1',
                        'file' => realpath(__DIR__ . '/../../..') . '/phraseanet.recorder.json',
                    ),
                    'limit' => 500,
                )
            ),
        );
    }

    public function testRecordConfigIsPassedToFactory()
    {
        $storage = $this->getMock('PhraseanetSDK\Recorder\Storage\StorageInterface');
        $storageFactory = $this->getMockBuilder('PhraseanetSDK\Recorder\Storage\StorageFactory')
            ->disableOriginalConstructor()
            ->getMock();

        $storageFactory->expects($this->once())
            ->method('create')
            ->with('memcached', array('host' => '127.0.0.1', 'file' => realpath(__DIR__ . '/../../..') . '/phraseanet.recorder.json'))
            ->will($this->returnValue($storage));

        $app = $this->getConfiguredApplication();
        $app->register(new PhraseanetSDKServiceProvider(), array(
            'recorder.config' => array(
                'type' => 'memcached',
                'options' => array(
                    'host' => '127.0.0.1'
                ),
                'limit' => 666,
            )
        ));
        $app['phraseanet-sdk.recorder.storage-factory'] = $storageFactory;
        $app['phraseanet-sdk.guzzle.history-plugin'] = $this->getMockBuilder('Guzzle\Plugin\History\HistoryPlugin')
            ->disableOriginalConstructor()
            ->getMock();

        $recorder = $app['phraseanet-sdk.recorder'];
        $this->assertEquals($storage, $recorder->getStorage());
        $this->assertEquals($app['phraseanet-sdk.guzzle.history-plugin'], $recorder->getPlugin());

    }

    private function getConfiguredApplication()
    {
        $app = new Application();
        $app->register(new ServiceControllerServiceProvider());

        return $app;
    }
}
