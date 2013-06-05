<?php

namespace PhraseanetSDK\Tests\Recorder\Storage;

use PhraseanetSDK\Recorder\Storage\MemcachedStorage;

class MemcachedStorageTest extends \PHPUnit_Framework_TestCase
{
    private function getDoctrineCacheMock()
    {
        return $this->getMockBuilder('Doctrine\Common\Cache\MemcachedCache')
            ->disableOriginalConstructor()
            ->getmock();
    }

    public function testFetchReturnsEMptyArrayIfNotFound()
    {
        $cache = $this->getDoctrineCacheMock();
        $cache->expects($this->once())
            ->method('fetch')
            ->with($this->isType('string'))
            ->will($this->returnValue(false));
        $storage = new MemcachedStorage($cache);
        $this->assertSame(array(), $storage->fetch());
    }

    public function testFetch()
    {
        $result = array('hello' => 'world');
        $cache = $this->getDoctrineCacheMock();
        $cache->expects($this->once())
            ->method('fetch')
            ->with($this->isType('string'))
            ->will($this->returnValue($result));
        $storage = new MemcachedStorage($cache);
        $this->assertSame($result, $storage->fetch());
    }

    public function testSave()
    {
        $data = array('hello' => 'world');
        $cache = $this->getDoctrineCacheMock();
        $cache->expects($this->once())
            ->method('save')
            ->with($this->isType('string'), $this->equalTo($data), $this->equalTo(0));
        $storage = new MemcachedStorage($cache);
        $storage->save($data);
    }
}
