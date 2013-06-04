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
