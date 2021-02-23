<?php

namespace PhraseanetSDK\Tests\Http;

use PhraseanetSDK\Http\APIGuzzleAdapter;
use PhraseanetSDK\Http\APIResponse;
use GuzzleHttp\ClientInterface;
use PhraseanetSDK\Http\GuzzleAdapterInterface;

class APIGuzzleAdapterTest extends \PHPUnit_Framework_TestCase
{
    public function testGetGuzzle()
    {
        $guzzle = $this->createMock(ClientInterface::class);

        $adapter = $this->createMock(GuzzleAdapterInterface::class);
        $adapter->expects($this->once())
            ->method('getGuzzle')
            ->will($this->returnValue($guzzle));

        /** @var GuzzleAdapterInterface $adapter */
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

        $adapter = $this->createMock(GuzzleAdapterInterface::class);
        $adapter->expects($this->once())
            ->method('call')
            ->with($method, $path, $query, $postFields, $files)
            ->will($this->returnValue(json_encode($response)));

        /** @var GuzzleAdapterInterface $adapter */
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

        $adapter = $this->createMock(GuzzleAdapterInterface::class);
        $adapter->expects($this->once())
            ->method('call')
            ->with($method, $path, $query, $postFields, $files)
            ->will($this->returnValue('non json string'));

        /** @var GuzzleAdapterInterface $adapter */
        $connected = new APIGuzzleAdapter($adapter);
        $this->setExpectedException('PhraseanetSDK\Exception\RuntimeException');
        $connected->call($method, $path, $query, $postFields, $files);
    }
}
