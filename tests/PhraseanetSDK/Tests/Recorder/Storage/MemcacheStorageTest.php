<?php

namespace PhraseanetSDK\Tests\Recorder\Storage;

use PhraseanetSDK\Recorder\Storage\MemcacheStorage;

class MemcacheStorageTest extends \PHPUnit_Framework_TestCase
{
    private function getDoctrineCacheMock()
    {
        return $this->getMockBuilder('Doctrine\Common\Cache\MemcacheCache')
            ->disableOriginalConstructor()
            ->getmock();
    }

    public function testFetchReturnsEmptyArrayIfNotFound()
    {
        if ( ! extension_loaded('memcache')) {
            $this->markTestSkipped('The ' . __CLASS__ .' requires the use of memcache');
        }

        $cache = $this->getDoctrineCacheMock();
        $cache->expects($this->once())
            ->method('fetch')
            ->with($this->isType('string'))
            ->will($this->returnValue(false));
        $storage = new MemcacheStorage($cache);
        $this->assertSame(array(), $storage->fetch());
    }

    public function testFetch()
    {
        if ( ! extension_loaded('memcache')) {
            $this->markTestSkipped('The ' . __CLASS__ .' requires the use of memcache');
        }

        $result = array('hello' => 'world');
        $cache = $this->getDoctrineCacheMock();
        $cache->expects($this->once())
            ->method('fetch')
            ->with($this->isType('string'))
            ->will($this->returnValue($result));
        $storage = new MemcacheStorage($cache);
        $this->assertSame($result, $storage->fetch());
    }

    public function testSave()
    {
        if ( ! extension_loaded('memcache')) {
            $this->markTestSkipped('The ' . __CLASS__ .' requires the use of memcache');
        }

        $data = array('hello' => 'world');
        $cache = $this->getDoctrineCacheMock();
        $cache->expects($this->once())
            ->method('save')
            ->with($this->isType('string'), $this->equalTo($data), $this->equalTo(0));
        $storage = new MemcacheStorage($cache);
        $storage->save($data);
    }
}
