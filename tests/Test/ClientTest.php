<?php

namespace Test;

use Guzzle;
use PhraseanetSDK\Client;
use PhraseanetSDK\Response;

class ClientTest extends \PHPUnit_Framework_TestCase
{

    public function testPOSTCall200()
    {
        $plugin = new Guzzle\Http\Plugin\MockPlugin();

        $plugin->addResponse(new Guzzle\Http\Message\Response(
                        200
                        , null
                        , $this->getSampleResponse('200')
                )
        );

        $clientHttp = new Guzzle\Http\Client(
                        'http://my.domain.tld/api/v{{version}}',
                        array('version' => 1)
        );

        $clientHttp->getEventDispatcher()->addSubscriber($plugin);

        $client = new Client('http://my.domain.tld/', '123456', '654321', $clientHttp);

        $response = $client->call('/path/to/ressource');

        $this->assertTrue($response instanceof Response);

        $this->assertEquals(200, $response->getHttpStatusCode());
    }

    public function testGETCall200()
    {
        $plugin = new Guzzle\Http\Plugin\MockPlugin();

        $plugin->addResponse(new Guzzle\Http\Message\Response(
                        200
                        , null
                        , $this->getSampleResponse('200')
                )
        );

        $clientHttp = new Guzzle\Http\Client(
                        'http://my.domain.tld/api/v{{version}}',
                        array('version' => 1)
        );

        $clientHttp->getEventDispatcher()->addSubscriber($plugin);

        $client = new Client('http://my.domain.tld/', '123456', '654321', $clientHttp);

        $response = $client->call('/path/to/ressource', array('key' => 'value'), 'GET');

        $this->assertTrue($response instanceof Response);

        $this->assertEquals(200, $response->getHttpStatusCode());
    }

    /**
     * @dataProvider methodProvider
     * @expectedException PhraseanetSDK\Exception\BadRequestException
     */
    public function testBadRequestException($method)
    {
        $plugin = new Guzzle\Http\Plugin\MockPlugin();

        $plugin->addResponse(new Guzzle\Http\Message\Response(200));

        $clientHttp = new Guzzle\Http\Client(
                        'http://my.domain.tld/api/v{{version}}',
                        array('version' => 1)
        );

        $clientHttp->getEventDispatcher()->addSubscriber($plugin);

        $client = new Client('http://my.domain.tld/', '123456', '654321', $clientHttp);

        $client->call('/path/to/ressource', array(), $method);
    }

    /**
     * @dataProvider httpCodeProvider
     * @expectedException PhraseanetSDK\Exception\BadResponseException
     */
    public function testBadResponseException($httpCode)
    {
        $plugin = new Guzzle\Http\Plugin\MockPlugin();

        $plugin->addResponse(new Guzzle\Http\Message\Response($httpCode));

        $clientHttp = new Guzzle\Http\Client(
                        'http://my.domain.tld/api/v{{version}}',
                        array('version' => 1)
        );

        $clientHttp->getEventDispatcher()->addSubscriber($plugin);

        $client = new Client('http://my.domain.tld/', '123456', '654321', $clientHttp);

        $client->call('/path/to/ressource');
    }

    public function testForceNoException()
    {
        $plugin = new Guzzle\Http\Plugin\MockPlugin();

        $plugin->addResponse(new Guzzle\Http\Message\Response(
                        401
                        , null
                        , $this->getSampleResponse('401')
                )
        );

        //no domain specified should raise an curlException
        $clientHttp = new Guzzle\Http\Client();

        $clientHttp->getEventDispatcher()->addSubscriber($plugin);

        $client = new Client('http://my.domain.tld/', '123456', '654321', $clientHttp);

        $response = $client->call('/path/to/ressource', array(), 'GET', false);
        
        $this->assertTrue($response instanceof Response);
    }

    public function methodProvider()
    {
        return array(
            array('HEAD'),
            array('DELETE'),
            array('PUT'),
            array('BLABLA'),
            array('OPTIONS'),
            array('TRACE')
        );
    }

    public function httpCodeProvider()
    {
        return array(
            array('400'),
            array('401'),
            array('403'),
            array('404'),
            array('405'),
            array('500'),
            array('503')
        );
    }

    private function getSampleResponse($filename)
    {
        $filename = __DIR__ . '/../ressources/response_samples/' . $filename . '.json';
        return file_get_contents($filename);
    }

}
