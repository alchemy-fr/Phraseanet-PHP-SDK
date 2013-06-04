<?php

namespace PhraseanetSDK\Cache;

use PhraseanetSDK\Exception\RuntimeException;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\MemcacheCache;
use Doctrine\Common\Cache\MemcachedCache;

class CacheFactory
{
    public function create($type, $host = null, $port = null)
    {
        switch(strtolower($type)) {
            case 'array':
                $cache = new ArrayCache();
                break;
            case 'memcache':
                $host = $host ? $host : '127.0.0.1';
                $port = $port ? (int) $port : 11211;

                $memcache = new \Memcache();
                $memcache->addServer($host,$port);

                $key = sprintf("%s:%s", $host, $port);
                $stats = @$memcache->getExtendedStats();

                if (!isset($stats[$key]) || false === $stats[$key]) {
                    throw new RuntimeException(sprintf("Memcache instance with host '%s' and port '%s' is not reachable", $host, $port));
                }

                $cache = new MemcacheCache();
                $cache->setMemcache($memcache);
                break;
            case 'memcached':
                $host = $host ? $host : '127.0.0.1';
                $port = $port ? (int) $port : 11211;

                $memcached = new \Memcached();
                $memcached->addServer($host,$port);

                $key = sprintf("%s:%s", $host, $port);
                $stats = @$memcached->getStats();

                if (!isset($stats[$key]) || false === $stats[$key] || '' === $stats[$key]['version']) {
                    throw new RuntimeException(sprintf("Memcached instance with host '%s' and port '%s' is not reachable", $host, $port));
                }

                $cache = new MemcachedCache();
                $cache->setMemcached($memcached);
                break;
            default:
                throw new RuntimeException(sprintf('Cache `%s` is not supported', $type));
                break;
        }

        $cache->setNamespace(md5(__DIR__));

        return $cache;
    }
}
