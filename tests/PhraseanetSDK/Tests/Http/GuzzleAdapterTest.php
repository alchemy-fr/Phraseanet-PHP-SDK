<?php

namespace PhraseanetSDK\Tests\Http;


use PhraseanetSDK\Exception\BadResponseException as SDK_BadResponseException;
use PhraseanetSDK\Http\GuzzleAdapter;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\BadResponseException as GuzzleBadResponseException;
use PhraseanetSDK\Exception\InvalidArgumentException as SDK_InvalidArgumentException;
use Guzzle\Http\Message\Response;
use Guzzle\Plugin\Mock\MockPlugin;

use GuzzleHttp\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

use PhraseanetSDK\Exception\RuntimeException as SDK_RuntimeException;



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
    public function testCall(
        $method, $path, array $query, array $postFields, array $files,
        $expected_options
    )
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

        $response = $this->createMock(ResponseInterface::class);
        $response->expects($this->any())
            ->method('getBody')
            ->will($this->returnValue($body));

        $guzzleClient = $this->createMock(ClientInterface::class);
        $guzzleClient->expects($this->once())
            ->method('request')
            ->with($method, $path, $expected_options)
            ->will($this->returnValue($response));

        /** @var ClientInterface $guzzleClient */
        $adapter = new GuzzleAdapter($guzzleClient);
        $this->assertEquals($body, $adapter->call($method, $path, $query, $postFields, $files));
    }

    public function provideCallParameters()
    {
        return array(
            array('GET',
                'path/to/resource',
                [],
                [],
                [],
                ['query'=>[], 'headers'=>['Accept'=>"application/json"]]
            ),
            array('GET', 'path/to/resource',
                ['q1' => 'qv1', 'q2' => 'qv2'],
                [],
                [],
                ['query'=>['q1'=>'qv1', 'q2'=>'qv2'], 'headers'=>['Accept'=>"application/json"]]
            ),
            array('POST',
                'path/to/resource',
                [],
                [],
                [],
                ['query'=>[], 'headers'=>['Accept'=>"application/json"]]
            ),
            array('POST',
                'path/to/resource',
                ['q1' => 'qv1', 'q2' => 'qv2'],
                [],
                [],
                ['query'=>['q1'=>'qv1', 'q2'=>'qv2'], 'headers'=>['Accept'=>"application/json"]]
            ),
            array(
                'POST',
                'path/to/resource',
                [],
                ['p1'=>'pv1', 'p2'=>'pv2'],
                [],
                ['query'=>[], 'form_params'=>['p1'=>'pv1', 'p2'=>'pv2'], 'headers'=>['Accept'=>"application/json"]]
            ),
            array(
                'POST',
                'path/to/resource',
                ['q1' => 'qv1', 'q2' => 'qv2'],
                ['p1'=>'pv1', 'p2'=>'pv2'],
                [],
                ['query'=>['q1'=>'qv1', 'q2'=>'qv2'], 'form_params'=>['p1'=>'pv1', 'p2'=>'pv2'], 'headers'=>['Accept'=>"application/json"]]
            ),
// todo: file is not yet implemented
//            array(
//                'POST',
//                'path/to/resource',
//                array('query1' => 'param1', 'query2' => 'param2'),
//                [],
//                array('file' => '/path/to/file')
//            ),
//            array(
//                'POST',
//                'path/to/resource',
//                [],
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
        $guzzleClient = $this->createMock(ClientInterface::class);

        /** @var ClientInterface $guzzleClient */
        $adapter = new GuzzleAdapter($guzzleClient);

        $this->expectException(SDK_InvalidArgumentException::class);
        $adapter->call('GET', '/path/to/resource', [], array('post' => 'value'));
    }

   public function testCallWithExceptionsBadResponseException()
    {
        $request = $this->createMock(RequestInterface::class);

        $guzzleClient = $this->createMock(ClientInterface::class);
        $guzzleClient->expects($this->once())
            ->method('request')
            ->will($this->returnValue($request));

        $response = $this->getMockBuilder(ResponseInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $guzzleClient->expects($this->once())
            ->method('request')
            ->will($this->throwException(new GuzzleBadResponseException('message', $request, $response)));

        /** @var ClientInterface $guzzleClient */
        $adapter = new GuzzleAdapter($guzzleClient);

        $this->expectException(SDK_BadResponseException::class);
        $adapter->call('GET', '/path/to/resource');
    }

    public function testCallWithExceptionsGuzzleException()
    {
        $guzzleClient = $this->createMock(ClientInterface::class);
        $guzzleClient->expects($this->once())
            ->method('request')
            ->will($this->throwException(new TestException()));

        /** @var ClientInterface $guzzleClient */
        $adapter = new GuzzleAdapter($guzzleClient);

        $this->expectException(SDK_RuntimeException::class);
        $adapter->call('GET', '/path/to/resource');
    }
}

class TestException extends \Exception implements GuzzleException
{
}
