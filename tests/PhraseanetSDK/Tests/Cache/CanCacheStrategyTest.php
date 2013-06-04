<?php

namespace PhraseanetSDK\Tests\Cache;

use PhraseanetSDK\Cache\CanCacheStrategy;

class CanCacheStrategyTest extends \PHPUnit_Framework_TestCase
{
    public function testCanCacheRequestAlwaysTrue()
    {
        $request = $this->getMock('Guzzle\Http\Message\RequestInterface');

        $canCache = new CanCacheStrategy();
        $this->assertTrue($canCache->canCacheRequest($request));
    }

    /**
     * @dataProvider provideSuccessVariants
     */
    public function testCanCacheResponseIfResponseIsSuccessful($success)
    {
        $response = $this->getMockBuilder('Guzzle\Http\Message\Response')
            ->disableOriginalConstructor()
            ->getMock();
        $response->expects($this->once())
            ->method('isSuccessful')
            ->will($this->returnValue($success));

        $canCache = new CanCacheStrategy();
        $this->assertSame($success, $canCache->canCacheResponse($response));
    }

    public function provideSuccessVariants()
    {
        return array(
            array(true),
            array(false),
        );
    }
}
