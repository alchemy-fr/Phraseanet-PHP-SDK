<?php

namespace PhraseanetSDK\Tests;

use PhraseanetSDK\PhraseanetSDKServiceProvider;
use Silex\Application;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
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
            ),
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
        );
    }

    private function getConfiguredApplication()
    {
        $app = new Application();

        $app->register(new ServiceControllerServiceProvider());
        $app->register(new UrlGeneratorServiceProvider());

        return $app;
    }
}
