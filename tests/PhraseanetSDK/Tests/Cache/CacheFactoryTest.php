<?php

namespace PhraseanetSDK\Tests\Cache;

use PhraseanetSDK\Cache\CacheFactory;

class CacheFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideValidParameters
     */
    public function testCreateSuccess($type, $host, $port, $instanceOf)
    {
        $factory = new CacheFactory();
        $this->assertInstanceOf($instanceOf, $factory->create($type, $host, $port));
    }

    public function provideValidParameters()
    {
        return array(
            array('memcache', '127.0.0.1', 11211, 'Doctrine\Common\Cache\MemcacheCache'),
            array('memcache', null, null, 'Doctrine\Common\Cache\MemcacheCache'),
            array('memcached', '127.0.0.1', 11211, 'Doctrine\Common\Cache\MemcachedCache'),
            array('memcached', null, null, 'Doctrine\Common\Cache\MemcachedCache'),
            array('array', '127.0.0.1', 11211, 'Doctrine\Common\Cache\ArrayCache'),
            array('array', null, null, 'Doctrine\Common\Cache\ArrayCache'),
        );
    }

    /**
     * @dataProvider provideInvalidParameters
     * @expectedException PhraseanetSDK\Exception\RuntimeException
     */
    public function testCreateFailure($type, $host, $port)
    {
        $factory = new CacheFactory();
        $factory->create($type, $host, $port);
    }

    public function provideInvalidParameters()
    {
        return array(
            array('memcache', 'nohost', 'noport'),
            array('memcached', 'nohost', 'noport'),
            array('unknown', 'nohost', 'noport'),
            array('unknown', null, null),
        );
    }
}
