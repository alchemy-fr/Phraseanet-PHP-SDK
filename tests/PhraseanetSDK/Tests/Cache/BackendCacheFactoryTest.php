<?php

namespace PhraseanetSDK\Tests\Cache;

use PhraseanetSDK\Cache\BackendCacheFactory;
use PhraseanetSDK\Exception\RuntimeException;

class BackendCacheFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideValidParameters
     */
    public function testCreateSuccess($type, $host, $port, $instanceOf, $classExists)
    {
        if (null !== $classExists) {
            if (!class_exists($classExists)) {
                $this->markTestSkipped(sprintf('Unable to find class %s', $classExists));
            }
        }

        $factory = new BackendCacheFactory();
        try {
            $this->assertInstanceOf($instanceOf, $factory->create($type, $host, $port));
        } catch (RuntimeException $e) {
            $this->assertContains(ucfirst(strtolower($type)), $e->getMessage());
        }
    }

    public function provideValidParameters()
    {
        return array(
            array('memcache', '127.0.0.1', 11211, 'Doctrine\Common\Cache\MemcacheCache', 'Memcache'),
            array('memcache', null, null, 'Doctrine\Common\Cache\MemcacheCache', 'Memcache'),
            array('memcached', '127.0.0.1', 11211, 'Doctrine\Common\Cache\MemcachedCache', 'Memcached'),
            array('memcached', null, null, 'Doctrine\Common\Cache\MemcachedCache', 'Memcached'),
            array('array', '127.0.0.1', 11211, 'Doctrine\Common\Cache\ArrayCache', null),
            array('array', null, null, 'Doctrine\Common\Cache\ArrayCache', null),
        );
    }

    /**
     * @dataProvider provideInvalidParameters
     * @expectedException PhraseanetSDK\Exception\RuntimeException
     */
    public function testCreateFailure($type, $host, $port, $classExists)
    {
        if (null !== $classExists) {
            if (!class_exists($classExists)) {
                $this->markTestSkipped(sprintf('Unable to find class %s', $classExists));
            }
        }

        $factory = new BackendCacheFactory();
        $factory->create($type, $host, $port);
    }

    public function provideInvalidParameters()
    {
        return array(
            array('memcache', 'nohost', 'noport', 'Memcache'),
            array('memcached', 'nohost', 'noport', 'Memcache'),
            array('unknown', 'nohost', 'noport', null),
            array('unknown', null, null, null),
        );
    }
}
