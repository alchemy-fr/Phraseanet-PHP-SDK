<?php

namespace Test;

use Guzzle;
use PhraseanetSDK\Client;
use PhraseanetSDK\Response;

class ClientTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers PhraseanetSDK\Client::__construct
     * @covers PhraseanetSDK\Client::isValidUrl
     * @covers PhraseanetSDK\Exception\InvalidArgumentException
     * @expectedException PhraseanetSDK\Exception\InvalidArgumentException
     */
    public function testInvalidUrl()
    {
        $clientHttp = new Guzzle\Http\Client(
                        'http://my.domain.tld/api/v{{version}}',
                        array('version' => 1)
        );

        new Client('badUrl', '123456', '654321', $clientHttp);
    }

    /**
     * @covers PhraseanetSDK\Client::__construct
     */
    public function testConstructor()
    {
        $clientHttp = new Guzzle\Http\Client(
                        'http://my.domain.tld/api/v{{version}}',
                        array('version' => 1)
        );

        $client = new Client('http://my.domain.tld/', '123456', '654321', $clientHttp);
    }

    /**
     * @covers PhraseanetSDK\Client::getAccessToken
     */
    public function testGetAccessToken()
    {
        $clientHttp = new Guzzle\Http\Client(
                        'http://my.domain.tld/api/v{{version}}',
                        array('version' => 1)
        );

        $client = new Client('http://my.domain.tld/', '123456', '654321', $clientHttp);
        $this->assertNull($client->getAccessToken());
    }

    /**
     * @covers PhraseanetSDK\Client::getHttpClient
     */
    public function testGetHttpClient()
    {
        $clientHttp = new Guzzle\Http\Client(
                        'http://my.domain.tld/api/v{{version}}',
                        array('version' => 1)
        );

        $client = new Client('http://my.domain.tld/', '123456', '654321', $clientHttp);
        $this->assertEquals($clientHttp, $client->getHttpClient());
    }

    /**
     * @covers PhraseanetSDK\Client::setAccessToken
     */
    public function testSetAccessToken()
    {
        $clientHttp = new Guzzle\Http\Client(
                        'http://my.domain.tld/api/v{{version}}',
                        array('version' => 1)
        );

        $client = new Client('http://my.domain.tld/', '123456', '654321', $clientHttp);
        $expected = '123456789';
        $client->setAccessToken($expected);
        $this->assertEquals($expected, $client->getAccessToken());
    }

    /**
     * @covers PhraseanetSDK\Client::setHttpClient
     */
    public function testSetHttpClient()
    {
        $clientHttp = new Guzzle\Http\Client(
                        'http://my.domain.tld/api/v{{version}}',
                        array('version' => 1)
        );

        $client = new Client('http://my.domain.tld/', '123456', '654321', $clientHttp);
        $expected = new Guzzle\Http\Client(
                        'http://my.domain2.tld/api2/v{{version}}',
                        array('version' => 2)
        );

        $client->setHttpClient($expected);
        $this->assertEquals($expected, $client->getHttpClient());
    }

    /**
     * @covers PhraseanetSDK\Client::setGrantType
     * @covers PhraseanetSDK\Exception\InvalidArgumentException
     * @expectedException PhraseanetSDK\Exception\InvalidArgumentException
     */
    public function testSetGrantTypeException()
    {
        $clientHttp = new Guzzle\Http\Client(
                        'http://my.domain.tld/api/v{{version}}',
                        array('version' => 1)
        );

        $client = new Client('http://my.domain.tld/', '123456', '654321', $clientHttp);


        $client->setGrantType('badGrantType');
    }

    /**
     * @covers PhraseanetSDK\Client::getCurrentUrl
     */
    public function testGetCurrentUrl()
    {
        $clientHttp = new Guzzle\Http\Client(
                        'http://my.domain.tld/api/v{{version}}',
                        array('version' => 1)
        );

        $client = new Client('http://my.domain.tld/', '123456', '654321', $clientHttp);

        $_SERVER['HTTPS'] = 'on';
        $client->setGrantType(Client::GRANT_TYPE_AUTHORIZATION);
        unset($_SERVER['HTTPS']);
        
        $_SERVER['HTTP_SSL_HTTPS'] = 'on';
        $client->setGrantType(Client::GRANT_TYPE_AUTHORIZATION);
        unset($_SERVER['HTTP_SSL_HTTPS']);
        
        $_SERVER['HTTP_X_FORWARDED_PROTO'] = 'https';
        $client->setGrantType(Client::GRANT_TYPE_AUTHORIZATION);
        unset($_SERVER['HTTP_X_FORWARDED_PROTO']);
    }

    /**
     * @covers PhraseanetSDK\Client::getAuthorizationUrl
     * @covers PhraseanetSDK\Exception\RuntimeException
     * @expectedException PhraseanetSDK\Exception\RuntimeException
     */
    public function testgetAuthorizationUrlException()
    {
        $clientHttp = new Guzzle\Http\Client(
                        'http://my.domain.tld/api/v{{version}}',
                        array('version' => 1)
        );

        $client = new Client('http://my.domain.tld/', '123456', '654321', $clientHttp);


        $client->getAuthorizationUrl();
    }

    /**
     * @covers PhraseanetSDK\Client::getAuthorizationUrl
     * @covers PhraseanetSDK\Client::getCurrentUrl
     * @covers PhraseanetSDK\Client::setGrantType
     */
    public function testSetGrantTypeAndGetAuthorizationUrl()
    {
        $clientHttp = new Guzzle\Http\Client(
                        'http://my.domain.tld/api/v{{version}}',
                        array('version' => 1)
        );
        
        $client = new Client('http://my.domain.tld/', '123456', '654321', $clientHttp);

        $client->setGrantType(Client::GRANT_TYPE_AUTHORIZATION);

        $url = $client->getAuthorizationUrl(array('admin', 'superadmin'));

        $this->assertEquals('http://my.domain.tld/api/oauthv2/authorize?response_type=code&client_id=123456&redirect_uri=http%3A%2F%2Fdev.phrasea.net%2Ftest.php%3Fkey%3Dvalue&scope=admin+superadmin', $url);
    }

    /**
     * @covers PhraseanetSDK\Client::retrieveAccessToken
     */
    public function testRetrieveAccessToken()
    {
        $plugin = new Guzzle\Http\Plugin\MockPlugin();

        $plugin->addResponse(new Guzzle\Http\Message\Response(
                        200
                        , null
                        , $this->getSampleResponse('access_token')
                )
        );

        $clientHttp = new Guzzle\Http\Client(
                        'http://my.domain.tld/api/v{{version}}',
                        array('version' => 1)
        );

        $clientHttp->getEventDispatcher()->addSubscriber($plugin);

        $_GET['code'] = '123456789';
        $client = new Client('http://my.domain.tld/', '123456', '654321', $clientHttp);
        $client->setGrantType(Client::GRANT_TYPE_AUTHORIZATION);
        $client->retrieveAccessToken();
        $this->assertEquals('987654321123456789', $client->getAccessToken());
        unset($_GET['code']);
    }

    /**
     * @covers PhraseanetSDK\Client::retrieveAccessToken
     * @covers PhraseanetSDK\Exception\AuthenticationException
     * @expectedException PhraseanetSDK\Exception\AuthenticationException
     */
    public function testRetrieveAccessTokenError()
    {
        $plugin = new Guzzle\Http\Plugin\MockPlugin();

        $plugin->addResponse(new Guzzle\Http\Message\Response(
                        200
                        , null
                        , $this->getSampleResponse('access_token')
                )
        );

        $clientHttp = new Guzzle\Http\Client(
                        'http://my.domain.tld/api/v{{version}}',
                        array('version' => 1)
        );

        $clientHttp->getEventDispatcher()->addSubscriber($plugin);

        $_GET['error'] = 'invalid_uri';

        $client = new Client('http://my.domain.tld/', '123456', '654321', $clientHttp);
        $client->setGrantType(Client::GRANT_TYPE_AUTHORIZATION);
        $client->retrieveAccessToken();
    }

    /**
     * @covers PhraseanetSDK\Client::logout
     */
    public function testLogout()
    {
        $clientHttp = new Guzzle\Http\Client(
                        'http://my.domain.tld/api/v{{version}}',
                        array('version' => 1)
        );

        $client = new Client('http://my.domain.tld/', '123456', '654321', $clientHttp);
        $client->setAccessToken('hello');
        $client->logout();
        $this->assertNull($client->getAccessToken());
    }

    /**
     * @covers PhraseanetSDK\Client::call
     */
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

        $client->setAccessToken("123456789");

        $response = $client->call('/path/to/ressource');

        $this->assertTrue($response instanceof Response);

        $this->assertEquals(200, $response->getHttpStatusCode());
    }

    /**
     * @covers PhraseanetSDK\Client::call
     */
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
     * @covers PhraseanetSDK\Client::call
     * @dataProvider methodProvider
     * @covers PhraseanetSDK\Exception\BadRequestException
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
     * @covers PhraseanetSDK\Client::call
     * @covers PhraseanetSDK\Exception\BadResponseException
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

    /**
     * @covers PhraseanetSDK\Client::call
     */
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
