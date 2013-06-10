<?php

namespace PhraseanetSDK\Tests\Http;

use PhraseanetSDK\Http\APIGuzzleAdapter;
use PhraseanetSDK\Http\APIResponse;

class APIGuzzleAdapterTest extends \PHPUnit_Framework_TestCase
{
    public function testGetGuzzle()
    {
        $guzzle = $this->getMock('Guzzle\Http\ClientInterface');

        $adapter = $this->getMock('PhraseanetSDK\Http\GuzzleAdapterInterface');
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
        $response = array('meta' => array(), 'response' => array());

        $adapter = $this->getMock('PhraseanetSDK\Http\GuzzleAdapterInterface');
        $adapter->expects($this->once())
            ->method('call')
            ->with()
            ->will($this->returnValue(json_encode($response)));

        $connected = new APIGuzzleAdapter($adapter);
        $apiResponse = $connected->call($method, $path, $query, $postFields);

        $this->assertInstanceOf('PhraseanetSDK\Http\APIResponse', $apiResponse);
        $this->assertEquals(new APIResponse(json_decode(json_encode($response))), $apiResponse);
    }

    public function testCallInvalidResponse()
    {
        $method = 'POST';
        $path = '/path/to/resource';
        $query = array('query' => 'value');
        $postFields = array('post' => 'field');

        $adapter = $this->getMock('PhraseanetSDK\Http\GuzzleAdapterInterface');
        $adapter->expects($this->once())
            ->method('call')
            ->with()
            ->will($this->returnValue('non json string'));

        $connected = new APIGuzzleAdapter($adapter);
        $this->setExpectedException('PhraseanetSDK\Exception\RuntimeException');
        $connected->call($method, $path, $query, $postFields);
    }
}
