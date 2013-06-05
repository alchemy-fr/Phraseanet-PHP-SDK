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
            $app['phraseanet-sdk.config'] = array(
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
            $app['phraseanet-sdk.config'] = array(
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
            $app['phraseanet-sdk.config'] = $config
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

    private function getConfiguredApplication()
    {
        $app = new Application();

        $app->register(new MonologServiceProvider());
        $app['monolog.handler'] = new NullHandler();

        return $app;
    }
}
