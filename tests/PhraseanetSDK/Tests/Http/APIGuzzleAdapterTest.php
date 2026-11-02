<?php

namespace PhraseanetSDK\Tests\Http;

use PhraseanetSDK\Http\APIGuzzleAdapter;
use PhraseanetSDK\Http\APIResponse;

class APIGuzzleAdapterTest extends \PHPUnit\Framework\TestCase
{
    public function testGetGuzzle()
    {
        $guzzle = $this->createMock('GuzzleHttp\ClientInterface');

        $adapter = $this->createMock('PhraseanetSDK\Http\GuzzleAdapterInterface');
        $adapter->expects($this->once())
            ->method('getGuzzle')
            ->will($this->returnValue($guzzle));

        $connected = new APIGuzzleAdapter($adapter);
        $this->assertSame($guzzle, $connected->getGuzzle());
    }

    public function testCall()
    {
        $method = 'POST';
        $path = '/path/to/resource';
        $query = array('query' => 'value');
        $postFields = array('post' => 'field');
        $files = array('file' => '/path/to/file');
        $response = array('meta' => array(), 'response' => array());

        $adapter = $this->createMock('PhraseanetSDK\Http\GuzzleAdapterInterface');
        $adapter->expects($this->once())
            ->method('call')
            ->with($method, $path, $query, $postFields, $files)
            ->will($this->returnValue(json_encode($response)));

        $connected = new APIGuzzleAdapter($adapter);
        $apiResponse = $connected->call($method, $path, $query, $postFields, $files);

        $this->assertInstanceOf('PhraseanetSDK\Http\APIResponse', $apiResponse);
        $this->assertEquals(new APIResponse(json_decode(json_encode($response))), $apiResponse);
    }

    public function testCallInvalidResponse()
    {
        $method = 'POST';
        $path = '/path/to/resource';
        $query = array('query' => 'value');
        $postFields = array('post' => 'field');
        $files = array('file' => '/path/to/file');

        $adapter = $this->createMock('PhraseanetSDK\Http\GuzzleAdapterInterface');
        $adapter->expects($this->once())
            ->method('call')
            ->with($method, $path, $query, $postFields, $files)
            ->will($this->returnValue('non json string'));

        $connected = new APIGuzzleAdapter($adapter);
        $this->expectException('PhraseanetSDK\Exception\RuntimeException');
        $connected->call($method, $path, $query, $postFields, $files);
    }
}
