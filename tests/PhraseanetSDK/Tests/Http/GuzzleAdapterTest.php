<?php

namespace PhraseanetSDK\Tests\Http;

use PhraseanetSDK\Http\GuzzleAdapter;
use Guzzle\Http\Exception\CurlException;
use Guzzle\Common\Exception\GuzzleException;
use Guzzle\Http\Exception\BadResponseException as GuzzleBadResponseException;

class GuzzleAdapterTest extends \PHPUnit_Framework_TestCase
{
    public function testGetters()
    {
        $baseUrl = 'http://phraseanet.com/api/v1/';

        $guzzle = $this->getMock('Guzzle\Http\ClientInterface');
        $guzzle->expects($this->any())
            ->method('getBaseUrl')
            ->will($this->returnValue($baseUrl));

        $adapter = new GuzzleAdapter($guzzle);
        $this->assertSame($guzzle, $adapter->getGuzzle());
        $this->assertSame($baseUrl, $adapter->getBaseUrl());
    }

    public function testSetUserAgent()
    {
        $userAgent = 'Special agent cooper';

        $guzzle = $this->getMock('Guzzle\Http\ClientInterface');
        $guzzle->expects($this->once())
            ->method('setUserAgent')
            ->with($userAgent);

        $adapter = new GuzzleAdapter($guzzle);
        $adapter->setUserAgent($userAgent);
    }

    /**
     * @dataProvider provideCallParameters
     */
    public function testCall($method, $path, $query, $postFields, $files)
    {
        if ('GET' === $method) {
            $request = $this->getMock('Guzzle\Http\Message\RequestInterface');
        } else {
            $request = $this->getMock('Guzzle\Http\Message\EntityEnclosingRequestInterface');

            $queryPostFields = $this->getMock('Guzzle\Http\QueryString');

            $request->expects($this->exactly(count($postFields)))
                ->method('getPostFields')
                ->will($this->returnValue($queryPostFields));

            $request->expects($this->exactly(count($files)))
                ->method('addPostFile');
        }

        $body = 'body '.mt_rand();

        $queryString = $this->getMock('Guzzle\Http\QueryString');

        $request->expects($this->exactly(count($query)))
            ->method('getQuery')
            ->will($this->returnValue($queryString));

        $response = $this->getMockBuilder('Guzzle\Http\Message\Response')
            ->disableOriginalConstructor()->getMock();
        $response->expects($this->any())
            ->method('getBody')
            ->with(true)
            ->will($this->returnValue($body));

        $request->expects($this->once())
            ->method('send')
            ->will($this->returnValue($response));

        $guzzle = $this->getMock('Guzzle\Http\ClientInterface');
        $guzzle->expects($this->once())
            ->method('createRequest')
            ->with($method, $path, array('accept' => 'application/json'))
            ->will($this->returnValue($request));

        $adapter = new GuzzleAdapter($guzzle);
        $this->assertEquals($body, $adapter->call($method, $path, $query, $postFields, $files));
    }

    public function provideCallParameters()
    {
        return array(
            array('GET', 'path/to/resource', array(), array(), array()),
            array('GET', 'path/to/resource', array('query1' => 'param1', 'query2' => 'param2'), array(), array()),
            array('POST', 'path/to/resource', array(), array(), array()),
            array('POST', 'path/to/resource', array('query1' => 'param1', 'query2' => 'param2'), array(), array()),
            array('POST', 'path/to/resource', array(), array('post1' => 'value1', 'post2' => 'value2', 'post3' => 'value3'), array()),
            array('POST', 'path/to/resource', array('query1' => 'param1', 'query2' => 'param2'), array('post1' => 'value1', 'post2' => 'value2', 'post3' => 'value3'), array()),
            array('POST', 'path/to/resource', array('query1' => 'param1', 'query2' => 'param2'), array(), array('file' => '/path/to/file')),
            array('POST', 'path/to/resource', array(), array('post1' => 'value1', 'post2' => 'value2', 'post3' => 'value3'), array('file' => '/path/to/file')),
            array('POST', 'path/to/resource', array('query1' => 'param1', 'query2' => 'param2'), array('post1' => 'value1', 'post2' => 'value2', 'post3' => 'value3'), array('file' => '/path/to/file')),
        );
    }

    public function testPostFieldsOnGETRequestThrowsException()
    {
        $request = $this->getMock('Guzzle\Http\Message\RequestInterface');

        $guzzle = $this->getMock('Guzzle\Http\ClientInterface');
        $guzzle->expects($this->once())
            ->method('createRequest')
            ->will($this->returnValue($request));

        $adapter = new GuzzleAdapter($guzzle);

        $this->setExpectedException('PhraseanetSDK\Exception\InvalidArgumentException');
        $adapter->call('GET', '/path/to/resource', array(), array('post' => 'value'));
    }

    public function testCallWithExceptionsCurlException()
    {
        $request = $this->getMock('Guzzle\Http\Message\RequestInterface');

        $guzzle = $this->getMock('Guzzle\Http\ClientInterface');
        $guzzle->expects($this->once())
            ->method('createRequest')
            ->will($this->returnValue($request));

        $request->expects($this->once())
            ->method('send')
            ->will($this->throwException(new CurlException()));

        $adapter = new GuzzleAdapter($guzzle);

        $this->setExpectedException('PhraseanetSDK\Exception\RuntimeException');
        $adapter->call('GET', '/path/to/resource');
    }

    public function testCallWithExceptionsBadResponseException()
    {
        $request = $this->getMock('Guzzle\Http\Message\RequestInterface');

        $guzzle = $this->getMock('Guzzle\Http\ClientInterface');
        $guzzle->expects($this->once())
            ->method('createRequest')
            ->will($this->returnValue($request));

        $response = $this->getMockBuilder('Guzzle\Http\Message\Response')
            ->disableOriginalConstructor()
            ->getMock();

        $request->expects($this->once())
            ->method('send')
            ->will($this->throwException(GuzzleBadResponseException::factory($request, $response)));

        $adapter = new GuzzleAdapter($guzzle);

        $this->setExpectedException('PhraseanetSDK\Exception\BadResponseException');
        $adapter->call('GET', '/path/to/resource');
    }

    public function testCallWithExceptionsGuzzleException()
    {
        $request = $this->getMock('Guzzle\Http\Message\RequestInterface');

        $guzzle = $this->getMock('Guzzle\Http\ClientInterface');
        $guzzle->expects($this->once())
            ->method('createRequest')
            ->will($this->returnValue($request));

        $request->expects($this->once())
            ->method('send')
            ->will($this->throwException(new TestException()));

        $adapter = new GuzzleAdapter($guzzle);

        $this->setExpectedException('PhraseanetSDK\Exception\RuntimeException');
        $adapter->call('GET', '/path/to/resource');
    }
}

class TestException extends \Exception implements GuzzleException
{
}
