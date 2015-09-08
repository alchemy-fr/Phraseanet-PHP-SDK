<?php

namespace PhraseanetSDK\Tests\Http;

use PhraseanetSDK\Http\APIResponse;

class APIResponseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \PhraseanetSDK\Exception\InvalidArgumentException
     */
    public function testBadResponse()
    {
        new APIResponse(new \stdClass());
    }

    public function testContructor()
    {
        $response = new APIResponse(json_decode($this->getSampleResponse(200)));
        $this->assertNotNull($response->getResult());
    }

    public function testGetResult()
    {
        $response = new APIResponse(json_decode($this->getSampleResponse(200)));
        $this->assertTrue(is_object($response->getResult()));
        $this->assertEquals(3, count(get_object_vars($response->getResult()->databoxes)));
    }

    public function testGetStatusCode()
    {
        $response = new APIResponse(json_decode($this->getSampleResponse(200)));
        $this->assertEquals(200, $response->getStatusCode());

        $response = new APIResponse(json_decode($this->getSampleResponse(401)));
        $this->assertEquals(401, $response->getStatusCode());
    }

    public function testIsOk()
    {
        $response = new APIResponse(json_decode($this->getSampleResponse(200)));
        $this->assertTrue($response->isOk());

        $response = new APIResponse(json_decode($this->getSampleResponse(401)));
        $this->assertFalse($response->isOk());

        $response = new APIResponse(json_decode($this->getSampleResponse(500)));
        $this->assertFalse($response->isOk());
    }

    public function testGetErrorMessage()
    {
        $response = new APIResponse(json_decode($this->getSampleResponse(500)));
        $this->assertEquals("something went wrong", $response->getErrorMessage());
    }

    public function testGetErrorDetails()
    {
        $response = new APIResponse(json_decode($this->getSampleResponse(500)));
        $this->assertEquals("server has encountered an unexpected error", $response->getErrorDetails());
    }

    public function testGetResponseTime()
    {
        $expected = new \DateTime('2011-07-27T10:17:26+02:00');
        $response = new APIResponse(json_decode($this->getSampleResponse(200)));
        $this->assertEquals($expected, $response->getResponseTime());
    }

    public function testGetUri()
    {
        $response = new APIResponse(json_decode($this->getSampleResponse(200)));
        $this->assertEquals('/api/v1/databoxes/list/', $response->getUri());
    }

    public function testGetMethod()
    {
        $response = new APIResponse(json_decode($this->getSampleResponse(200)));
        $this->assertEquals('GET', $response->getMethod());
    }

    public function testIsEmpty()
    {
        $response = new APIResponse(json_decode($this->getSampleResponse("empty")));
        $this->assertTrue($response->isEmpty());
    }

    public function testGetCharset()
    {
        $response = new APIResponse(json_decode($this->getSampleResponse(200)));
        $this->assertEquals('UTF-8', $response->getCharset());
    }

    public function testGetApiVersion()
    {
        $response = new APIResponse(json_decode($this->getSampleResponse(200)));
        $this->assertEquals('1.0', $response->getApiVersion());
    }

    private function getSampleResponse($filename)
    {
        return file_get_contents(__DIR__ . '/../../../resources/response_samples/' . $filename . '.json');
    }
}
