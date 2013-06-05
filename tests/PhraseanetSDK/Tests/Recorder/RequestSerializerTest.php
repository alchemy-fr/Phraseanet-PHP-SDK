<?php

namespace PhraseanetSDK\Tests\Recorder;

use PhraseanetSDK\Recorder\RequestSerializer;

class RequestSerializerTest extends \PHPUnit_Framework_TestCase
{
    public function testSerialize()
    {
        $expected = array(
            'query'   => array('request query'),
            'params'  => array(),
            'method'  => 'request method',
            'path'    => 'request path',
            'headers' => array('request headers'),
        );

        $request = $this->getMock('Guzzle\Http\Message\RequestInterface');
        $request->expects($this->once())->method('getMethod')->will($this->returnValue('request method'));
        $request->expects($this->once())->method('getPath')->will($this->returnValue('request path'));

        $query = $this->getMockBuilder('Guzzle\Http\QueryString')
            ->disableOriginalConstructor()
            ->getMock();
        $query->expects($this->once())
            ->method('toArray')
            ->will($this->returnValue(array('request query')));

        $headers = $this->getMockBuilder('Guzzle\Http\Message\Header\HeaderCollection')
            ->disableOriginalConstructor()
            ->getMock();
        $headers->expects($this->once())
            ->method('toArray')
            ->will($this->returnValue(array('request headers')));

        $request->expects($this->once())->method('getQuery')->will($this->returnValue($query));
        $request->expects($this->once())->method('getHeaders')->will($this->returnValue($headers));

        $serializer = new RequestSerializer();
        $this->assertSame($expected, $serializer->serialize($request));
    }

    public function testUnserialize()
    {
        $serializedRequest = array(
            'query'   => array('request query'),
            'params'  => array(),
            'method'  => 'request method',
            'path'    => 'request path',
            'headers' => array('request headers'),
        );

        $request = $this->getMock('Guzzle\Http\Message\RequestInterface');

        $query = $this->getMockBuilder('Guzzle\Http\QueryString')
            ->disableOriginalConstructor()
            ->getMock();
        $query->expects($this->once())
            ->method('replace')
            ->will($this->returnValue(array('request query')));

        $request->expects($this->once())->method('getQuery')->will($this->returnValue($query));

        $client = $this->getMock('Guzzle\Http\ClientInterface');
        $client->expects($this->once())
            ->method('createRequest')
            ->with('request method', 'request path', array('request headers'))
            ->will($this->returnValue($request));

        $serializer = new RequestSerializer();
        $this->assertSame($request, $serializer->unserialize($client, $serializedRequest));
    }
}
