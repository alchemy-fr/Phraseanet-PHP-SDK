<?php

namespace PhraseanetSDK\Tests\Recorder;

use PHPUnit_Framework_TestCase;
use PhraseanetSDK\Recorder\Recorder;
use GuzzleHttp\Message\Request;
use PhraseanetSDK\Recorder\Filters\FilterInterface;
use Guzzle\Plugin\History\HistoryPlugin;
use PhraseanetSDK\Recorder\Storage\StorageInterface;
use PhraseanetSDK\Recorder\RequestExtractor;

class RecorderTest extends PHPUnit_Framework_TestCase
{
    public function testSave()
    {
        $history = $this->createMock(HistoryPlugin::class);
        $storage = $this->createMock(StorageInterface::class);
        $extractor = $this->createMock(RequestExtractor::class);
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

        /**
         * @var HistoryPlugin $history
         * @var StorageInterface $storage
         * @var RequestExtractor $extractor
         */
        $recorder = new Recorder($history, $storage, $extractor, $limit);
        $recorder->save();
    }

    public function testWithFilter()
    {
        $history = $this->createMock(HistoryPlugin::class);
        $storage = $this->createMock(StorageInterface::class);
        $extractor = $this->createMock(RequestExtractor::class);

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

        /**
         * @var HistoryPlugin $history
         * @var StorageInterface $storage
         * @var RequestExtractor $extractor
         */
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
