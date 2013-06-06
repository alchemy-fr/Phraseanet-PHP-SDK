<?php

namespace PhraseanetSDK\Tests;

use PhraseanetSDK\Client;
use PhraseanetSDK\HttpAdapter\Response;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractClient extends \PHPUnit_Framework_TestCase
{
    protected $logger;
    protected $clientId = '123456';
    protected $clientSecret = '654321';

    public function setUp()
    {
        $logger = new \Monolog\Logger('test');
        $logger->pushHandler(new \Monolog\Handler\NullHandler());

        $this->logger = $logger;
    }

    /**
     * @return PhraseanetSDK\HttpAdapter\HttpAdapterInterface
     */
    abstract public function getAdapter();

    /**
     * @covers PhraseanetSDK\Client::__construct
     */
    public function testGetClient()
    {
        return $this->getSDKClient();
    }

    /**
     * @depends testGetClient
     */
    public function testGetEntityManager($client)
    {
        $this->assertInstanceOf('PhraseanetSDK\EntityManager', $client->getEntityManager());
    }

    /**
     * @covers PhraseanetSDK\Client::getAccessToken
     * @depends testGetClient
     */
    public function testGetAccessToken($client)
    {
        $this->assertNull($client->getAccessToken());
    }

    /**
     * @covers PhraseanetSDK\Client::getHttpClient
     * @covers PhraseanetSDK\Client::setHttpClient
     * @depends testGetClient
     */
    public function testGetHttpClient($client)
    {
        $expected = $this->getAdapter();
        $client->setHttpClient($expected);
        $this->assertEquals($expected, $client->getHttpClient());
    }

    /**
     * @covers PhraseanetSDK\Client::setAccessToken
     * @depends testGetClient
     */
    public function testSetAccessToken($client)
    {
        $expected = '123456789';
        $client->setAccessToken($expected);
        $this->assertEquals($expected, $client->getAccessToken());
    }

    /**
     * @covers PhraseanetSDK\Client::setGrantType
     * @covers PhraseanetSDK\Exception\InvalidArgumentException
     * @covers PhraseanetSDK\Exception\ExceptionInterface
     * @expectedException PhraseanetSDK\Exception\InvalidArgumentException
     * @depends testGetClient
     */
    public function testSetGrantTypeException($client)
    {
        $client->setGrantType('badGrantType');
    }

    /**
     * @covers PhraseanetSDK\Client::getAuthorizationUrl
     * @covers PhraseanetSDK\Exception\RuntimeException
     * @expectedException PhraseanetSDK\Exception\RuntimeException
     * @depends testGetClient
     */
    public function testgetAuthorizationUrlException($client)
    {
        $client->getAuthorizationUrl();
    }

    /**
     * @covers PhraseanetSDK\Client::getAuthorizationUrl
     * @covers PhraseanetSDK\Client::setGrantType
     * @covers PhraseanetSDK\Client::getGrantType
     * @covers PhraseanetSDK\Client::getGrantInformations
     * @covers PhraseanetSDK\Client::getUrlWithoutOauth2Parameters
     * @depends testGetClient
     */
    public function testSetGrantTypeAndGetAuthorizationUrl($client)
    {
        $host = 'dev.phrasea.net';
        $query = '/test.php';

        $request = new Request(array('key'   => 'value', 'scope' => 'scope_test'), array(), array(), array(), array(), array('SERVER_PORT'  => 80, 'HTTP_HOST'    => $host, 'REQUEST_URI'  => $query, 'QUERY_STRING' => 'key=value'));

        $client->setGrantType(Client::GRANT_TYPE_AUTHORIZATION, null, $request);

        $this->assertEquals(Client::GRANT_TYPE_AUTHORIZATION, $client->getGrantType());

        $grantInfos = $client->getGrantInformations();

        $this->assertArrayHasKey('redirect_uri', $grantInfos);
        $this->assertRegExp('/' . str_replace(array('.'), array('\.'), $host) . '\w*' . str_replace(array('.', '?', '/'), array('\.', '\?', '\\/'), $query) . '/', $grantInfos['redirect_uri']);

        $url = $client->getAuthorizationUrl(array('admin', 'superadmin'));

        $this->assertEquals('http://my.domain.tld/api/oauthv2/authorize?response_type=code&client_id=123456&redirect_uri=http%3A%2F%2Fdev.phrasea.net%2Ftest.php%3Fkey%3Dvalue&scope=admin+superadmin', $url);

        $this->assertEquals(Client::GRANT_TYPE_AUTHORIZATION, $client->getGrantType());
        $this->assertTrue(is_array($client->getGrantInformations()));
        $this->assertArrayHasKey('scope', $client->getGrantInformations());
        $this->assertArrayHasKey('redirect_uri', $client->getGrantInformations());
    }

    /**
     * @covers PhraseanetSDK\Client::retrieveAccessToken
     */
    public function testRetrieveAccessToken()
    {
        $request = new Request(array('code' => '123456789'));

        $client = $this->getSDKClient($this->getSampleResponse('access_token'));
        $client->setGrantType(Client::GRANT_TYPE_AUTHORIZATION, array(), $request);
        $client->retrieveAccessToken($request);

        $this->assertEquals('987654321123456789', $client->getAccessToken());
    }

    /**
     * @covers PhraseanetSDK\Client::retrieveAccessToken
     * @covers PhraseanetSDK\Exception\AuthenticationException
     * @expectedException PhraseanetSDK\Exception\AuthenticationException
     */
    public function testRetrieveAccessTokenError()
    {
        $request = new Request(array('error' => 'invalid_uri'));

        $client = $this->getSDKClient($this->getSampleResponse('access_token'));
        $client->setGrantType(Client::GRANT_TYPE_AUTHORIZATION);
        $client->retrieveAccessToken($request);
    }

    /**
     * @covers PhraseanetSDK\Client::retrieveAccessToken
     */
    public function testRetrieveAccessTokenNoCode()
    {
        $request = new Request();

        $client = $this->getSDKClient($this->getSampleResponse('access_token'));
        $client->setGrantType(Client::GRANT_TYPE_AUTHORIZATION);
        $token = $client->retrieveAccessToken($request);
        $this->assertNull($token);
    }

    /**
     * @covers PhraseanetSDK\Client::logout
     * @depends testGetClient
     */
    public function testLogout($client)
    {
        $client->setAccessToken('hello');
        $client->logout();
        $this->assertNull($client->getAccessToken());
    }

    /**
     * @covers PhraseanetSDK\Client::call
     * @depends testGetClient
     */
    public function testPOSTCall200()
    {
        $client = $this->getSDKClient($this->getSampleResponse('200'));
        $client->setAccessToken("123456789");
        $response = $client->call('POST', '/path/to/ressource');

        $this->assertTrue($response instanceof Response);
        $this->assertEquals(200, $response->getHttpStatusCode());
    }

    /**
     * @covers PhraseanetSDK\Client::call
     */
    public function testGETCall200()
    {
        $client = $this->getSDKClient($this->getSampleResponse('200'));
        $response = $client->call('POST', '/path/to/ressource', array('key' => 'value'));

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
        $client = $this->getSDKClient();
        $client->call($method, '/path/to/ressource');
    }

    /**
     * @covers PhraseanetSDK\Client::call
     * @covers PhraseanetSDK\Exception\BadResponseException
     * @dataProvider httpCodeProvider
     * @expectedException PhraseanetSDK\Exception\BadResponseException
     */
    public function testBadResponseException($httpCode)
    {
        $client = $this->getSDKClient('', $httpCode);
        $client->call('GET', '/path/to/ressource');
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
        $filename = __DIR__ . '/../../resources/response_samples/' . $filename . '.json';

        return file_get_contents($filename);
    }

    private function getSDKClient($response = null, $code = 200)
    {
        return new Client($this->clientId, $this->clientSecret, $this->getAdapter($response, $code), $this->logger);
    }
}
