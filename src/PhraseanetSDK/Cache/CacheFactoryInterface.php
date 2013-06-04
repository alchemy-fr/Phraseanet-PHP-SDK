<?php

namespace PhraseanetSDK\Cache;

use Doctrine\Common\Cache\Cache;
use Guzzle\Cache\DoctrineCacheAdapter;
use PhraseanetSDK\Exception\RuntimeException;

interface CacheFactoryInterface
{
    /**
     * Creates a Doctrine cache
     *
     * @param string $type
     * @param string|null $host
     * @param integer|null $port
     *
     * @return Cache
     *
     * @throws RuntimeException
     */
    public function create($type, $host = null, $port = null);

    /**
     * Creates a GuzzleCacheAdapter
     *
     * @param string $type
     * @param string|null $host
     * @param integer|null $port
     *
     * @return DoctrineCacheAdapter
     *
     * @throws RuntimeException
     */
    public function createGuzzleCacheAdapter($type, $host = null, $port = null);
}
