<?php

/*
 * This file is part of Phraseanet SDK.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhraseanetSDK\Recorder\Storage;

use PhraseanetSDK\Exception\RuntimeException;
use PhraseanetSDK\Exception\InvalidArgumentException;
use PhraseanetSDK\Cache\CacheFactoryInterface;

class StorageFactory
{
    private $cacheFactory;

    public function __construct(CacheFactoryInterface $cacheFactory)
    {
        $this->cacheFactory = $cacheFactory;
    }

    /**
     * Creates a StorageInterface
     *
     * @param string $type
     * @param array  $options
     *
     * @return StorageInterface
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function create($type, array $options)
    {
        switch (strtolower($type)) {
            case 'file':
                if (!isset($options['file'])) {
                    throw new InvalidArgumentException('Missing option file');
                }

                return new FilesystemStorage($options['file']);
            case 'memcache':
                $host = isset($options['host']) ? $options['host'] : null;
                $port = isset($options['port']) ? $options['port'] : null;

                return new MemcacheStorage($this->cacheFactory->create('memcache', $host, $port));
            case 'memcached':
                $host = isset($options['host']) ? $options['host'] : null;
                $port = isset($options['port']) ? $options['port'] : null;

                return new MemcachedStorage($this->cacheFactory->create('memcached', $host, $port));
            default:
                throw new InvalidArgumentException(sprintf('Unknown storage %s', $type));
        }
    }
}
