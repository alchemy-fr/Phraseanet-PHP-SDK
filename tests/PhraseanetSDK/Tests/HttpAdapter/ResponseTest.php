<?php

namespace PhraseanetSDK\Tests\HttpAdapter;

use PhraseanetSDK\HttpAdapter\Response;

class ResponseTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers PhraseanetSDK\HttpAdapter\Response::__construct
     * @expectedException PhraseanetSDK\Exception\InvalidArgumentException
     */
    public function testBadResponse()
    {
        new Response(new \stdClass());
    }

    /**
     * @covers PhraseanetSDK\HttpAdapter\Response::__construct
     */
    public function testContructor()
    {
        $response = new Response(json_decode($this->getSampleResponse(200)));
        $this->assertNotNull($response->getResult());
    }

    /**
     * @covers PhraseanetSDK\HttpAdapter\Response::getResult
     */
    public function testGetResult()
    {
        $response = new Response(json_decode($this->getSampleResponse(200)));
        $this->assertTrue(is_object($response->getResult()));
        $this->assertEquals(3, count(get_object_vars($response->getResult()->databoxes)));
    }

    /**
     * @covers PhraseanetSDK\HttpAdapter\Response::getHttpStatusCode
     */
    public function testGetHttpStatusCode()
    {
        $response = new Response(json_decode($this->getSampleResponse(200)));
        $this->assertEquals(200, $response->getHttpStatusCode());

        $response = new Response(json_decode($this->getSampleResponse(401)));
        $this->assertEquals(401, $response->getHttpStatusCode());
    }

    /**
     * @covers PhraseanetSDK\HttpAdapter\Response::isOk
     */
    public function testIsOk()
    {
        $response = new Response(json_decode($this->getSampleResponse(200)));
        $this->assertTrue($response->isOk());

        $response = new Response(json_decode($this->getSampleResponse(401)));
        $this->assertFalse($response->isOk());

        $response = new Response(json_decode($this->getSampleResponse(500)));
        $this->assertFalse($response->isOk());
    }

    /**
     * @covers PhraseanetSDK\HttpAdapter\Response::getErrorMessage
     */
    public function testGetErrorMessage()
    {
        $response = new Response(json_decode($this->getSampleResponse(500)));
        $this->assertEquals("something went wrong", $response->getErrorMessage());
    }

    /**
     * @covers PhraseanetSDK\HttpAdapter\Response::getErrorDetails
     */
    public function testGetErrorDetails()
    {
        $response = new Response(json_decode($this->getSampleResponse(500)));
        $this->assertEquals("server has encountered an unexpected error", $response->getErrorDetails());
    }

    /**
     * @covers PhraseanetSDK\HttpAdapter\Response::getResponseTime
     */
    public function testGetResponseTime()
    {
        $expected = new \DateTime('2011-07-27T10:17:26+02:00');
        $response = new Response(json_decode($this->getSampleResponse(200)));
        $this->assertEquals($expected, $response->getResponseTime());
    }

    /**
     * @covers PhraseanetSDK\HttpAdapter\Response::getUri
     */
    public function testGetUri()
    {
        $response = new Response(json_decode($this->getSampleResponse(200)));
        $this->assertEquals('/api/v1/databoxes/list/', $response->getUri());
    }

    /**
     * @covers PhraseanetSDK\HttpAdapter\Response::getMethod
     */
    public function testGetMethod()
    {
        $response = new Response(json_decode($this->getSampleResponse(200)));
        $this->assertEquals('GET', $response->getMethod());
    }

    /**
     * @covers PhraseanetSDK\HttpAdapter\Response::isEmpty
     */
    public function testIsEmpty()
    {
       $response = new Response(json_decode($this->getSampleResponse("empty")));
        $this->assertTrue($response->isEmpty());
    }

    /**
     * @covers PhraseanetSDK\HttpAdapter\Response::getCharset
     */
    public function testGetCharset()
    {
        $response = new Response(json_decode($this->getSampleResponse(200)));
        $this->assertEquals('UTF-8', $response->getCharset());
    }

    /**
     * @covers PhraseanetSDK\HttpAdapter\Response::getApiVersion
     */
    public function testGetApiVersion()
    {
        $response = new Response(json_decode($this->getSampleResponse(200)));
        $this->assertEquals('1.0', $response->getApiVersion());
    }

    private function getSampleResponse($filename)
    {
        $filename = __DIR__ . '/../../resources/response_samples/' . $filename . '.json';

        return file_get_contents($filename);
    }
}
