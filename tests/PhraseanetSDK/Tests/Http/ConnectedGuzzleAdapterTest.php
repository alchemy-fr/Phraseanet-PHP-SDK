<?php

namespace PhraseanetSDK\Tests\Http;

use PhraseanetSDK\Http\ConnectedGuzzleAdapter;
use GuzzleHttp\ClientInterface;
use PhraseanetSDK\Http\GuzzleAdapterInterface;

class ConnectedGuzzleAdapterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideCallParameters
     * @param string $method
     * @param string $path
     * @param array $query
     * @param array $postFields
     * @param string $token
     * @param array $files
     */
    public function testCall($method, $path, array $query, array $postFields, $token, array $files)
    {
        $response = mt_rand() . 'response';

        $adapter = $this->createMock(GuzzleAdapterInterface::class);
        $adapter->expects($this->once())
            ->method('call')
            ->with($method, $path, array_replace($query, ['oauth_token' => $token]), $postFields, $files)
            ->will($this->returnValue($response));

        /** @var GuzzleAdapterInterface $adapter */
        $connected = new ConnectedGuzzleAdapter($token, $adapter);
        $connected->call($method, $path, $query, $postFields, $files);
    }

    public function provideCallParameters()
    {
        return array(
            array(
                'GET',
                '/path/to/resource',
                array(),
                array(),
                'token-' . mt_rand(),
                array()
            ),
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
        $adapter = $this->createMock(GuzzleAdapterInterface::class);

        /** @var GuzzleAdapterInterface $adapter */
        $connected = new ConnectedGuzzleAdapter('$token', $adapter);
        $this->assertEquals('$token', $connected->getToken());
        $connected->setToken('new token');
        $this->assertEquals('new token', $connected->getToken());
    }

    public function testGetGuzzle()
    {
        $guzzle = $this->createMock(ClientInterface::class);

        $adapter = $this->createMock(GuzzleAdapterInterface::class);
        $adapter->expects($this->once())
            ->method('getGuzzle')
            ->will($this->returnValue($guzzle));

        /** @var GuzzleAdapterInterface $adapter */
        $connected = new ConnectedGuzzleAdapter('$token', $adapter);
        $this->assertSame($guzzle, $connected->getGuzzle());
    }
}
