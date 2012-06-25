<?php

namespace Test\Repository;

use PhraseanetSDK\Client;
use Guzzle\Http\Plugin\MockPlugin;
use Guzzle\Http\Message\Response;
use Guzzle\Http\Client as GuzzleClient;
use Monolog\Logger;
use Monolog\Handler\NullHandler;

abstract class Repository extends \PHPUnit_Framework_TestCase
{

    protected function getClient($response)
    {
        $plugin = new MockPlugin();
        $plugin->addResponse(new Response(
                200
                , null
                , $response
            )
        );

        $clientHttp = new GuzzleClient(
                'http://my.domain.tld/api/v{{version}}',
                array('version' => 1)
        );
        $clientHttp->getEventDispatcher()->addSubscriber($plugin);

        $logger = new Logger('tests');
        $logger->pushHandler(new NullHandler());

        return new Client('123456', '654321', $clientHttp, $logger);
    }
}
