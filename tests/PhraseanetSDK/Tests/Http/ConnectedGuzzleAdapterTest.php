<?php

namespace PhraseanetSDK\Tests\Http;

use PhraseanetSDK\Http\ConnectedGuzzleAdapter;

class ConnectedGuzzleAdapterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideCallParameters
     */
    public function testCall($method, $path, $query, $postFields, $token)
    {
        $response = mt_rand() . 'response';

        $adapter = $this->getMock('PhraseanetSDK\Http\GuzzleAdapterInterface');
        $adapter->expects($this->once())
            ->method('call')
            ->with($method, $path, array_replace($query, array('oauth_token' => $token)), $postFields)
            ->will($this->returnValue($response));

        $connected = new ConnectedGuzzleAdapter($token, $adapter);
        $connected->call($method, $path, $query, $postFields);
    }

    public function provideCallParameters()
    {
        return array(
            array('GET', '/path/to/resource', array(), array(), 'token-'.mt_rand()),
            array('GET', '/path/to/resource', array('query1' => 'value1', 'oauth_token' => 'custom_token'), array(), 'token-'.mt_rand()),
            array('POST', '/path/to/resource', array('query1' => 'value1', 'oauth_token' => 'custom_token'), array('post' => 'value'), 'token-'.mt_rand()),
            array('POST', '/path/to/resource', array('query1' => 'value1'), array('post' => 'value'), 'token-'.mt_rand()),
        );
    }

    public function testGetSetToken()
    {
        $adapter = $this->getMock('PhraseanetSDK\Http\GuzzleAdapterInterface');

        $connected = new ConnectedGuzzleAdapter('$token', $adapter);
        $this->assertEquals('$token', $connected->getToken());
        $connected->setToken('new token');
        $this->assertEquals('new token', $connected->getToken());
    }

    public function testGetGuzzle()
    {
        $guzzle = $this->getMock('Guzzle\Http\ClientInterface');

        $adapter = $this->getMock('PhraseanetSDK\Http\GuzzleAdapterInterface');
        $adapter->expects($this->once())
            ->method('getGuzzle')
            ->will($this->returnValue($guzzle));

        $connected = new ConnectedGuzzleAdapter('$token', $adapter);
        $this->assertSame($guzzle, $connected->getGuzzle());
    }
}
