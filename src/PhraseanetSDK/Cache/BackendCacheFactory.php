<?php

/*
 * This file is part of Phraseanet SDK.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhraseanetSDK\Cache;

use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\MemcacheCache;
use Doctrine\Common\Cache\MemcachedCache;
use PhraseanetSDK\Exception\RuntimeException;

class BackendCacheFactory
{
    public function create($type, $host = null, $port = null)
    {
        $host = $host ? $host : '127.0.0.1';
        $port = $port ? (int) $port : 11211;

        switch (strtolower($type)) {
            case 'array':
                $cache = $this->createArray();
                break;
            case 'memcache':
                $cache = $this->createMemcache($host, $port);
                break;
            case 'memcached':
                $cache = $this->createMemcached($host, $port);
                break;
            default:
                throw new RuntimeException(sprintf('Cache `%s` is not supported', $type));
                break;
        }

        $cache->setNamespace(md5(__DIR__));

        return $cache;
    }

    private function createArray()
    {
        return new ArrayCache();
    }

    private function createMemcache($host, $port)
    {
        $memcache = new \Memcache();
        $memcache->addServer($host,$port);

        $key = sprintf("%s:%s", $host, $port);
        $stats = @$memcache->getExtendedStats();

        if (!isset($stats[$key]) || false === $stats[$key]) {
            throw new RuntimeException(sprintf("Memcache instance with host '%s' and port '%s' is not reachable", $host, $port));
        }

        $cache = new MemcacheCache();
        $cache->setMemcache($memcache);

        return $cache;
    }

    private function createMemcached($host, $port)
    {
        $memcached = new \Memcached();
        $memcached->addServer($host,$port);

        $key = sprintf("%s:%s", $host, $port);
        $stats = @$memcached->getStats();

        if (!isset($stats[$key]) || false === $stats[$key] || '' === $stats[$key]['version']) {
            throw new RuntimeException(sprintf("Memcached instance with host '%s' and port '%s' is not reachable", $host, $port));
        }

        $cache = new MemcachedCache();
        $cache->setMemcached($memcached);

        return $cache;
    }
}
