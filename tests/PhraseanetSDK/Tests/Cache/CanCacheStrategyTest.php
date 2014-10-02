<?php

namespace PhraseanetSDK\Tests\Cache;

use PhraseanetSDK\Cache\CanCacheStrategy;

class CanCacheStrategyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideSuccessVariants
     */
    public function testCanCacheResponseIfResponseIsSuccessful($success, $url, $cacheable)
    {
        $response = $this->getMockBuilder('Guzzle\Http\Message\Response')
            ->disableOriginalConstructor()
            ->getMock();
        $response->expects($this->once())
            ->method('getEffectiveUrl')
            ->will($this->returnValue($url));

        $response->expects($this->any())
            ->method('isSuccessful')
            ->will($this->returnValue($success));

        $response->expects($this->any())
                 ->method('canCache')
                 ->will($this->returnValue(true));

        $canCache = new CanCacheStrategy();
        $this->assertSame($cacheable, $canCache->canCacheResponse($response));
    }

    public function provideSuccessVariants()
    {
        return array(
            array(true, 'http://example.com/api/v1/record/search/', true),
            array(false, 'http://example.com/api/v1/record/search/', false),
            array(true, 'http://example.com/api/v1/monitor/scheduler/', false),
            array(false, 'http://example.com/api/v1/monitor/scheduler/', false),
        );
    }
}
