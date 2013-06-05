<?php

namespace PhraseanetSDK\Tests;

use PhraseanetSDK\PhraseanetSDKServiceProvider;
use Monolog\Handler\NullHandler;
use Silex\Application;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\WebProfilerServiceProvider;
use Silex\Provider\TwigServiceProvider;
use PhraseanetSDK\Cache\CanCacheStrategy;
use PhraseanetSDK\Cache\RevalidationFactory;
use PhraseanetSDK\Cache\CacheFactory;
use PhraseanetSDK\Exception\ExceptionInterface;

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
                'client-id' => 'sdfmqlsdkfm',
                'secret'    => 'eoieep',
                'url'       => 'https://bidule.net',
            )
        ));
        $app->boot();

        $this->assertInstanceOf($instanceOf, $app[$name]);
    }

    public function provideServices()
    {
        return array(
            array('phraseanet-sdk', 'PhraseanetSDK\Client'),
            array('phraseanet-sdk.cache.factory', 'PhraseanetSDK\Cache\CacheFactory'),
            array('phraseanet-sdk.guzzle.revalidation-factory', 'PhraseanetSDK\Cache\RevalidationFactory'),
            array('phraseanet-sdk.guzzle.can-cache-strategy', 'PhraseanetSDK\Cache\CanCacheStrategy'),
            array('phraseanet-sdk.recorder', 'PhraseanetSDK\Recorder\Recorder'),
            array('phraseanet-sdk.player', 'PhraseanetSDK\Recorder\Player'),
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
        $app->boot();

        $this->assertInstanceOf($instanceOf, $app[$name]);
    }

    /**
     * @dataProvider provideRecorderEnabledOptions
     */
    public function testHistoryPluginLoadedIfRecorderEnabled($enable, $count)
    {
        $app = $this->getConfiguredApplication();
        $app->register(new PhraseanetSDKServiceProvider());
        $app['phraseanet-sdk.recorder.enabled'] = $enable;

        $app->boot();

        $plugins = $app['phraseanet-sdk.guzzle.plugins'];
        $this->assertCount($count, $plugins);
        if (0 < $count) {
            $this->assertInstanceOf('Guzzle\Plugin\History\HistoryPlugin', array_pop($plugins));
        }
    }

    public function provideRecorderEnabledOptions()
    {
        return array(
            array(true, 1),
            array(false, 0),
        );
    }

    /**
     * @dataProvider provideWebProfilerEnabledOptions
     */
    public function testHistoryPluginLoadedIfProfilerEnabled($enabled, $count)
    {
        $app = $this->getConfiguredApplication();
        if ($enabled) {
            $app->register(new TwigServiceProvider());
            $app->register(new WebProfilerServiceProvider(), array(
                'profiler.cache_dir' => __DIR__ . '/cache',
            ));
        }
        $app->register(new PhraseanetSDKServiceProvider());

        $app->boot();

        $plugins = $app['phraseanet-sdk.guzzle.plugins'];
        $this->assertCount($count, $plugins);
        if (0 < $count) {
            $this->assertInstanceOf('Guzzle\Plugin\History\HistoryPlugin', array_pop($plugins));
        }
    }

    public function provideWebProfilerEnabledOptions()
    {
        return array(
            array(true, 1),
            array(false, 0),
        );
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

        $app['phraseanet-sdk.recorder.enabled'] = true;
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
     * @dataProvider provideVariousConfigs
     */
    public function testConfigMerge($config, $expected)
    {
        $app = $this->getConfiguredApplication();
        $app->register(new PhraseanetSDKServiceProvider(), array(
            'phraseanet-sdk.config' => $config
        ));
        // triggers build
        try {
           $app['phraseanet-sdk'];
        } catch (ExceptionInterface $e) {

        }
        $this->assertEquals($expected, $app['phraseanet-sdk.config']);
    }

    public function provideVariousConfigs()
    {
        $revalidation = $this->getMock('PhraseanetSDK\Cache\RevalidationFactoryInterface');
        $canCache = $this->getMock('Guzzle\Plugin\Cache\CanCacheStrategyInterface');
        $factory = $this->getMock('PhraseanetSDK\Cache\CacheFactoryInterface');
        $plugins = array('plugin');

        return array(
            array(array(
                'cache'     => array(
                    'factory' => $factory,
                ),
                'guzzle' => array(
                    'plugins' => $plugins,
                    'can-cache-strategy' => $canCache,
                    'revalidation-factory' => $revalidation,
                )
            ),array(
                'cache'     => array(
                    'factory' => $factory,
                ),
                'guzzle' => array(
                    'plugins' => $plugins,
                    'can-cache-strategy' => $canCache,
                    'revalidation-factory' => $revalidation,
                )
            )),
            array(array(
                'client-id' => 'sdfmqlsdkfm',
                'secret'    => 'eoieep',
                'url'       => 'https://bidule.net',
            ),array(
                'client-id' => 'sdfmqlsdkfm',
                'secret'    => 'eoieep',
                'url'       => 'https://bidule.net',
                'cache'     => array(
                    'factory' => new CacheFactory(),
                ),
                'guzzle' => array(
                    'plugins' => array(),
                    'can-cache-strategy' => new CanCacheStrategy,
                    'revalidation-factory' => new RevalidationFactory,
                )
            )),
            array(array(
            ),array(
                'cache'     => array(
                    'factory' => new CacheFactory(),
                ),
                'guzzle' => array(
                    'plugins' => array(),
                    'can-cache-strategy' => new CanCacheStrategy,
                    'revalidation-factory' => new RevalidationFactory,
                )
            )),
        );
    }

    /**
     * @dataProvider provideVariousRecorderConfigs
     */
    public function testRecorderConfigMerge($config, $expected)
    {
        $app = $this->getConfiguredApplication();
        $app->register(new PhraseanetSDKServiceProvider(), array(
            'phraseanet-sdk.recorder.config' => $config
        ));

        $app['phraseanet-sdk.recorder'];
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
            'phraseanet-sdk.recorder.config' => array(
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
        $this->assertEquals(666, $recorder->getLimit());
        $this->assertEquals($app['phraseanet-sdk.guzzle.history-plugin'], $recorder->getPlugin());

    }

    private function getConfiguredApplication()
    {
        $app = new Application();

        $app->register(new MonologServiceProvider());
        $app['monolog.handler'] = new NullHandler();

        return $app;
    }
}
