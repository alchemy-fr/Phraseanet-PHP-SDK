<?php

namespace PhraseanetSDK\Tests\Http;


use PhraseanetSDK\Exception\BadResponseException;
use PhraseanetSDK\Http\GuzzleAdapter;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\BadResponseException as GuzzleBadResponseException;
use Guzzle\Http\Message\Response;
use Guzzle\Plugin\Mock\MockPlugin;

use GuzzleHttp\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;



class GuzzleAdapterTest extends \PHPUnit_Framework_TestCase
{
    public function testGetters()
    {
        $baseUrl = 'http://phraseanet.com/api/v1/';
        $guzzle = $this->createMock(ClientInterface::class);

        /** @var ClientInterface $guzzle */
        $adapter = new GuzzleAdapter($guzzle);
        $adapter->setBaseUrl($baseUrl);
        $this->assertSame($guzzle, $adapter->getGuzzle());
        $this->assertSame($baseUrl, $adapter->getBaseUrl());

        // test user-agent is trivial
        $userAgent = 'Special agent cooper';
        $adapter->setUserAgent($userAgent);
        $this->assertSame($userAgent, $adapter->getUserAgent());
    }

    public function testCreate()
    {
        $endpoint = 'http://phraseanet.com/api/';
        $adapter = GuzzleAdapter::create($endpoint);
        $this->assertEquals($endpoint, $adapter->getBaseUrl());

        $endpoint = 'http://phraseanet.com';
        $adapter = GuzzleAdapter::create($endpoint);
        $this->assertEquals($endpoint . '/api/', $adapter->getBaseUrl());
    }

    /**
     * @dataProvider provideCallParameters
     * @param string $method
     * @param string $path
     * @param array $query
     * @param array $postFields
     * @param array $files
     * @throws BadResponseException
     */
    public function testCall($method, $path, array $query, array $postFields, array $files)
    {
//        if ($method === 'GET') {
            $request = $this->getMockBuilder(RequestInterface::class)
                ->disableOriginalConstructor()
                ->getMock();
//        }
//        else {
//            $request = $this->createMock('Guzzle\Http\Message\EntityEnclosingRequestInterface');
//
//            $queryPostFields = $this->createMock('Guzzle\Http\QueryString');
//
//            $request->expects($this->exactly(count($postFields)))
//                ->method('getPostFields')
//                ->will($this->returnValue($queryPostFields));
//
//            $request->expects($this->exactly(count($files)))
//                ->method('addPostFile');
//        }

        $body = 'body ' . mt_rand();

//        $queryString = $this->createMock('Guzzle\Http\QueryString');
//
//        $request->expects($this->exactly(count($query)))
//            ->method('getQuery')
//            ->will($this->returnValue($queryString));

        $response = $this->getMockBuilder(ResponseInterface::class)
            ->disableOriginalConstructor()->getMock();
        $response->expects($this->any())
            ->method('getBody')
            ->with([true])
            ->will($this->returnValue($body));

//        $request->expects($this->once())
//            ->method('send')
//            ->will($this->returnValue($response));

        $guzzle = $this->createMock(ClientInterface::class);
        $guzzle->expects($this->once())
            ->method('request')
            ->with($method, $path, [
                'Accept' => 'application/json'
            ])
            ->will($this->returnValue($request));

        /** @var ClientInterface $guzzle */
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
            array(
                'POST',
                'path/to/resource',
                array(),
                array('post1' => 'value1', 'post2' => 'value2', 'post3' => 'value3'),
                array()
            ),
            array(
                'POST',
                'path/to/resource',
                array('query1' => 'param1', 'query2' => 'param2'),
                array('post1' => 'value1', 'post2' => 'value2', 'post3' => 'value3'),
                array()
            ),
// todo: file is not yet implemented
//            array(
//                'POST',
//                'path/to/resource',
//                array('query1' => 'param1', 'query2' => 'param2'),
//                array(),
//                array('file' => '/path/to/file')
//            ),
//            array(
//                'POST',
//                'path/to/resource',
//                array(),
//                array('post1' => 'value1', 'post2' => 'value2', 'post3' => 'value3'),
//                array('file' => '/path/to/file')
//            ),
//            array(
//                'POST',
//                'path/to/resource',
//                array('query1' => 'param1', 'query2' => 'param2'),
//                array('post1' => 'value1', 'post2' => 'value2', 'post3' => 'value3'),
//                array('file' => '/path/to/file')
//            ),
        );
    }

    public function testPostFieldsOnGETRequestThrowsException()
    {
        $request = $this->createMock(RequestInterface::class);

        $guzzle = $this->createMock(ClientInterface::class);
        $guzzle->expects($this->once())
            ->method('request')
            ->will($this->returnValue($request));

        /** @var ClientInterface $guzzle */
        $adapter = new GuzzleAdapter($guzzle);

        $this->setExpectedException('PhraseanetSDK\Exception\InvalidArgumentException');
        $adapter->call('GET', '/path/to/resource', array(), array('post' => 'value'));
    }

   public function testCallWithExceptionsBadResponseException()
    {
        $request = $this->createMock(RequestInterface::class);

        $guzzle = $this->createMock(ClientInterface::class);
//        $guzzle->expects($this->once())
//            ->method('request')
//            ->will($this->returnValue($request));

        $response = $this->getMockBuilder(ResponseInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

       $guzzle->expects($this->once())
            ->method('request')
            ->will($this->throwException(new GuzzleBadResponseException('message', $request, $response)));

        /** @var ClientInterface $guzzle */
        $adapter = new GuzzleAdapter($guzzle);

        $this->setExpectedException('PhraseanetSDK\Exception\BadResponseException');
        $adapter->call('GET', '/path/to/resource');
    }

    public function testCallWithExceptionsGuzzleException()
    {
        $request = $this->createMock(RequestInterface::class);

        $guzzle = $this->createMock(ClientInterface::class);
        $guzzle->expects($this->once())
            ->method('request')
            ->will($this->throwException(new TestException()));

//        $request->expects($this->once())
//            ->method('request')
//            ->will($this->throwException(new TestException()));

        /** @var ClientInterface $guzzle */
        $adapter = new GuzzleAdapter($guzzle);

        $this->setExpectedException('PhraseanetSDK\Exception\RuntimeException');
        $adapter->call('GET', '/path/to/resource');
    }

    public function testClientWithCacheDoNotExecuteQueries()
    {
        $cache = $this->getMockBuilder('Guzzle\Cache\CacheAdapterInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $cache->expects($this->atLeast(3))
            ->method('fetch');

        $mock = new MockPlugin();
        $response = new Response(200, null, json_encode(array(
            'meta' => array(),
            'response' => array(),
        )));
        $mock->addResponse($response);
        $mock->addResponse($response);
        $mock->addResponse($response);

        $endpoint = 'http://phraseanet.com/api/v1/';
        $adapter = GuzzleAdapter::create($endpoint);

        $adapter->getGuzzle()->addSubscriber($mock);
        $adapter->getGuzzle()->addSubscriber(new CachePlugin($cache));

        $adapter->call('GET', '/url');
        $adapter->call('GET', '/url');
        $adapter->call('GET', '/url');
    }
}

class TestException extends \Exception implements GuzzleException
{
}
