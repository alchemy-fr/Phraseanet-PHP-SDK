<?php

namespace PhraseanetSDK\Tests\Recorder;

use PHPUnit_Framework_TestCase;
use PhraseanetSDK\Recorder\RequestExtractor;
use Psr\Http\Message\RequestInterface;

class RequestExtractorTest extends PHPUnit_Framework_TestCase
{
    public function testExtract()
    {
        $expected = array(
            'query'       => array('params' => 'value'),
            'post-fields' => array(),
            'method'      => 'POST',
            'path'        => '/v1/path/to/request',
        );

        $request = $this->createMock(RequestInterface::class);
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
