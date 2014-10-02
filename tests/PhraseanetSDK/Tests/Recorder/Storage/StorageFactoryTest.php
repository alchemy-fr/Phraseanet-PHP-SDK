<?php

namespace PhraseanetSDK\Tests\Recorder\Storage;

use PhraseanetSDK\Exception\RuntimeException;
use PhraseanetSDK\Recorder\Storage\StorageFactory;
use PhraseanetSDK\Cache\BackendCacheFactory;

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

        $factory = new StorageFactory(new BackendCacheFactory());
        try {
            $this->assertInstanceOf($instanceOf, $factory->create($type, $options));
        } catch (RuntimeException $e) {
            $this->assertContains(ucfirst(strtolower($type)), $e->getMessage());
        }
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
        $factory = new StorageFactory(new BackendCacheFactory());
        $factory->create($type, $options);
    }

    public function provideInvalidCacheConfigs()
    {
        return array(
            array('unknow', array(), null),
        );
    }
}
