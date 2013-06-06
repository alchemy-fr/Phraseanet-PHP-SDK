<?php

namespace PhraseanetSDK\Tests\Recorder;

use PhraseanetSDK\Recorder\RequestExtractor;

class RequestExtractorTest extends \PHPUnit_Framework_TestCase
{
    public function testExtract()
    {
        $expected = array(
            'query'       => array('request' => 'query'),
            'post-fields' => array(),
            'method'      => 'request method',
            'path'        => 'request path',
        );

        $request = $this->getMock('Guzzle\Http\Message\RequestInterface');
        $request->expects($this->once())->method('getMethod')->will($this->returnValue('request method'));
        $request->expects($this->once())->method('getPath')->will($this->returnValue('/api/v1/request path'));

        $query = $this->getMockBuilder('Guzzle\Http\QueryString')
            ->disableOriginalConstructor()
            ->getMock();
        $query->expects($this->once())
            ->method('toArray')
            ->will($this->returnValue(array('request' => 'query')));

        $request->expects($this->once())->method('getQuery')->will($this->returnValue($query));

        $extractor = new RequestExtractor();
        $this->assertSame($expected, $extractor->extract($request));
    }
}
