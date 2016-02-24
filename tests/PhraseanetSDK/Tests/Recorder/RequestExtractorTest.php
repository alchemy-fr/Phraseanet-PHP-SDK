<?php

namespace PhraseanetSDK\Tests\Recorder;

use PhraseanetSDK\Recorder\RequestExtractor;

class RequestExtractorTest extends \PHPUnit_Framework_TestCase
{
    public function testExtract()
    {
        $expected = array(
            'query'       => array('params' => 'value'),
            'post-fields' => array(),
            'method'      => 'POST',
            'path'        => '/v1/path/to/request',
        );

        $request = $this->getMock('Guzzle\Http\Message\RequestInterface');
        $request->expects($this->once())->method('getMethod')->will($this->returnValue('POST'));
        $request->expects($this->once())->method('getPath')->will($this->returnValue('/api/v1/path/to/request'));

        $query = $this->getMockBuilder('Guzzle\Http\QueryString')
            ->disableOriginalConstructor()
            ->getMock();
        $query->expects($this->once())
            ->method('toArray')
            ->will($this->returnValue(array('params' => 'value')));

        $request->expects($this->once())->method('getQuery')->will($this->returnValue($query));

        $extractor = new RequestExtractor();
        $this->assertSame($expected, $extractor->extract($request));
    }
}
