<?php

namespace PhraseanetSDK\Tests\Recorder\Storage;

use PhraseanetSDK\Recorder\Storage\StorageFactory;
use PhraseanetSDK\Cache\CacheFactory;

class StorageFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideCacheConfigs
     */
    public function testCreate($type, $options, $instanceOf, $test)
    {
        if (null !== $test && !class_exists($test)) {
            $this->markTestSkipped("Extension $test not loaded");
        }

        $factory = new StorageFactory(new CacheFactory());
        $this->assertInstanceOf($instanceOf, $factory->create($type, $options));
    }

    public function provideCacheConfigs()
    {
        return array(
            array('file', array('file' => __DIR__ . '/here-test.json'), 'PhraseanetSDK\Recorder\Storage\FilesystemStorage', null),
            array('FILE', array('file' => __DIR__ . '/here-test.json'), 'PhraseanetSDK\Recorder\Storage\FilesystemStorage', null),
            array('memcache', array(), 'PhraseanetSDK\Recorder\Storage\MemcacheStorage', 'Memcache'),
            array('MEMCACHE', array(), 'PhraseanetSDK\Recorder\Storage\MemcacheStorage', 'Memcache'),
            array('memcached', array(), 'PhraseanetSDK\Recorder\Storage\MemcachedStorage', 'Memcached'),
            array('MEMCACHED', array(), 'PhraseanetSDK\Recorder\Storage\MemcachedStorage', 'Memcached'),
        );
    }

    /**
     * @dataProvider provideInvalidCacheConfigs
     * @expectedException InvalidArgumentException
     */
    public function testCreateFailure($type, $options)
    {
        $factory = new StorageFactory(new CacheFactory());
        $factory->create($type, $options);
    }

    public function provideInvalidCacheConfigs()
    {
        return array(
            array('unknow', array(), null),
        );
    }
}
