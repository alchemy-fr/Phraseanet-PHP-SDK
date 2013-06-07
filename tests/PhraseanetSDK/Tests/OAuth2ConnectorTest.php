<?php

namespace PhraseanetSDK\Tests;

use PhraseanetSDK\OAuth2Connector;
use PhraseanetSDK\Exception\AuthenticationException;

class OAuth2ConnectorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideAuthorizationUrlParameters
     */
    public function testGetAuthorizationUrl($expectedUrl, $baseUrl, $redirectUri, $parameters, $scopes)
    {
        $clientId = 'api-client-id';
        $secret = 'api-client-secret';

        $adapter = $this->getMockBuilder('PhraseanetSDK\Http\GuzzleAdapter')
            ->disableOriginalConstructor()
            ->getMock();
        $adapter->expects($this->any())
            ->method('getBaseUrl')
            ->will($this->returnValue($baseUrl));

        $connector = new OAuth2Connector($adapter, $clientId, $secret);
        $url = $connector->getAuthorizationUrl($redirectUri, $parameters, $scopes);
        $this->assertSame($expectedUrl, $url);
    }

    public function provideAuthorizationUrlParameters()
    {
        return array(
            array(
                'http://phraseanet.com/api/oauthv2/authorize?redirect_uri='.urlencode('http://consumer.com/callback/').'&response_type=code&client_id=api-client-id&scope=',
                'http://phraseanet.com/api/v1/',
                'http://consumer.com/callback/',
                array(),
                array(),
            ),
            array(
                'http://phraseanet.com/api/oauthv2/authorize?redirect_uri='.urlencode('http://consumer.com/callback/').'&response_type=code&client_id=api-client-id&scope=scope1+scope2&extra=param%C3%A8ter',
                'http://phraseanet.com/api/v1/',
                'http://consumer.com/callback/',
                array('extra' => 'paramèter'),
                array('scope1', 'scope2'),
            ),
        );
    }

    public function testRetrieveAccessToken()
    {
        $clientId = 'api-client-id';
        $secret = 'api-client-secret';
        $code = md5(microtime(true));
        $redirectUri = 'http://consumer.com/callback';
        $baseUrl = 'http://phraseanet.com/api/v1/';
        $accessToken = md5(microtime(true)).'access';

        $adapter = $this->getMockBuilder('PhraseanetSDK\Http\GuzzleAdapter')
            ->disableOriginalConstructor()
            ->getMock();
        $adapter->expects($this->any())
            ->method('getBaseUrl')
            ->will($this->returnValue($baseUrl));

        $adapter->expects($this->once())
            ->method('call')
            ->with('POST', 'http://phraseanet.com/api/oauthv2/token', array(), array(
                'grant_type' => OAuth2Connector::GRANT_TYPE_AUTHORIZATION,
                'redirect_uri' => $redirectUri,
                'client_id' => $clientId,
                'client_secret' => $secret,
                'code' => $code,
            ))
            ->will($this->returnValue(json_encode(array('access_token' => $accessToken))));

        $connector = new OAuth2Connector($adapter, $clientId, $secret);
        $this->assertEquals($accessToken, $connector->retrieveAccessToken($code, $redirectUri));
    }


    public function testRetrieveAccessTokenWithBadResponse()
    {
        $clientId = 'api-client-id';
        $secret = 'api-client-secret';
        $code = md5(microtime(true));
        $redirectUri = 'http://consumer.com/callback';
        $baseUrl = 'http://phraseanet.com/api/v1/';
        $accessToken = md5(microtime(true)).'access';

        $adapter = $this->getMockBuilder('PhraseanetSDK\Http\GuzzleAdapter')
            ->disableOriginalConstructor()
            ->getMock();
        $adapter->expects($this->any())
            ->method('getBaseUrl')
            ->will($this->returnValue($baseUrl));

        $badResponse = $this->getMockBuilder('PhraseanetSDK\Exception\BadResponseException')
            ->disableOriginalConstructor()
            ->getMock();
        $badResponse->expects($this->once())
            ->method('getResponseBody')
            ->will($this->returnValue('{"error": "expired token"}'));

        $adapter->expects($this->once())
            ->method('call')
            ->with('POST', 'http://phraseanet.com/api/oauthv2/token', array(), array(
                'grant_type' => OAuth2Connector::GRANT_TYPE_AUTHORIZATION,
                'redirect_uri' => $redirectUri,
                'client_id' => $clientId,
                'client_secret' => $secret,
                'code' => $code,
            ))
            ->will($this->throwException($badResponse));

        $connector = new OAuth2Connector($adapter, $clientId, $secret);
        try {
            $this->assertEquals($accessToken, $connector->retrieveAccessToken($code, $redirectUri));
            $this->fail('An exception should have been raised');
        } catch (AuthenticationException $e) {
            $this->assertEquals('expired token', $e->getMessage());
        }
    }
}
