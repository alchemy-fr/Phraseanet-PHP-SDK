<?php

namespace PhraseanetSDK\Tests\Http;

use PhraseanetSDK\Http\ConnectedGuzzleAdapter;

class ConnectedGuzzleAdapterTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider provideCallParameters
     */
    public function testCall($method, $path, $query, $postFields, $token, $files)
    {
        $response = mt_rand() . 'response';

        $adapter = $this->createMock('PhraseanetSDK\Http\GuzzleAdapterInterface');
        $adapter->expects($this->once())
            ->method('call')
            ->with($method, $path, array_replace($query, array('oauth_token' => $token)), $postFields, $files)
            ->will($this->returnValue($response));

        $connected = new ConnectedGuzzleAdapter($token, $adapter);
        $connected->call($method, $path, $query, $postFields, $files);
    }

    public static function provideCallParameters()
    {
        return array(
            array('GET', '/path/to/resource', array(), array(), 'token-' . mt_rand(), array()),
            array(
                'GET',
                '/path/to/resource',
                array('query1' => 'value1', 'oauth_token' => 'custom_token'),
                array(),
                'token-' . mt_rand(),
                array()
            ),
            array(
                'POST',
                '/path/to/resource',
                array('query1' => 'value1', 'oauth_token' => 'custom_token'),
                array('post' => 'value'),
                'token-' . mt_rand(),
                array('file' => '/path/to/file')
            ),
            array(
                'POST',
                '/path/to/resource',
                array('query1' => 'value1'),
                array('post' => 'value'),
                'token-' . mt_rand(),
                array('file' => '/path/to/file')
            ),
        );
    }

    public function testGetSetToken()
    {
        $adapter = $this->createMock('PhraseanetSDK\Http\GuzzleAdapterInterface');

        $connected = new ConnectedGuzzleAdapter('$token', $adapter);
        $this->assertEquals('$token', $connected->getToken());
        $connected->setToken('new token');
        $this->assertEquals('new token', $connected->getToken());
    }

    public function testGetGuzzle()
    {
        $guzzle = $this->createMock('GuzzleHttp\ClientInterface');

        $adapter = $this->createMock('PhraseanetSDK\Http\GuzzleAdapterInterface');
        $adapter->expects($this->once())
            ->method('getGuzzle')
            ->will($this->returnValue($guzzle));

        $connected = new ConnectedGuzzleAdapter('$token', $adapter);
        $this->assertSame($guzzle, $connected->getGuzzle());
    }
}
