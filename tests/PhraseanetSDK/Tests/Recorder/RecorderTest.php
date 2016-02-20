<?php

namespace PhraseanetSDK\Tests\Recorder;

use PhraseanetSDK\Recorder\Recorder;
use Guzzle\Http\Message\Request;
use PhraseanetSDK\Recorder\Filters\FilterInterface;

class RecorderTest extends \PHPUnit_Framework_TestCase
{
    public function testSave()
    {
        $history = $this->getMock('Guzzle\Plugin\History\HistoryPlugin');
        $storage = $this->getMock('PhraseanetSDK\Recorder\Storage\StorageInterface');
        $extractor = $this->getMock('PhraseanetSDK\Recorder\RequestExtractor');
        $limit = 3;

        $request1 = new Request('GET', '/path1');
        $request2 = new Request('GET', '/path2');
        $request3 = new Request('GET', '/path3');
        $request4 = new Request('GET', '/path4');

        $requests = array($request1, $request4);
        $containedData = array($request1, $request2, $request3);

        $storage->expects($this->once())
            ->method('fetch')
            ->will($this->returnValue($containedData));
        $history->expects($this->once())
            ->method('getIterator')
            ->will($this->returnValue($requests));

        $extractor->expects($this->any())
            ->method('extract')
            ->will($this->returnCallback(function ($data) {
                return $data;
            }));

        $storage->expects($this->once())
            ->method('save')
            ->with(array(
                $request1,
                $request2,
                $request3,
                $request1,
                $request4,
            ));

        $recorder = new Recorder($history, $storage, $extractor, $limit);
        $recorder->save();
    }

    public function testWithFilter()
    {
        $history = $this->getMock('Guzzle\Plugin\History\HistoryPlugin');
        $storage = $this->getMock('PhraseanetSDK\Recorder\Storage\StorageInterface');
        $extractor = $this->getMock('PhraseanetSDK\Recorder\RequestExtractor');

        $request1 = new Request('GET', '/path1');
        $request2 = new Request('GET', '/path2');
        $request3 = new Request('GET', '/path3');
        $request4 = new Request('GET', '/path4');

        $requests = array($request1, $request4);
        $containedData = array($request1, $request2, $request3);

        $storage->expects($this->once())
            ->method('fetch')
            ->will($this->returnValue($containedData));
        $history->expects($this->once())
            ->method('getIterator')
            ->will($this->returnValue($requests));

        $extractor->expects($this->any())
            ->method('extract')
            ->will($this->returnCallback(function ($data) {
                return $data;
            }));

        $storage->expects($this->once())
            ->method('save')
            ->with(array(
                $request4,
            ));

        $recorder = new Recorder($history, $storage, $extractor);
        $recorder->addFilter(new TestFilter());
        $recorder->save();
    }
}

class TestFilter implements FilterInterface
{
    public function apply(array &$data)
    {
        $data = array(end($data));
    }
}
