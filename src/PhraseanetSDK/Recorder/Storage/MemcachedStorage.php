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

use Doctrine\Common\Cache\MemcachedCache;

class MemcachedStorage implements StorageInterface
{
    private $memcached;
    private $key;

    public function __construct(MemcachedCache $memcached)
    {
        $this->memcached = $memcached;
        $this->key = 'VCR-recording-'.md5(__DIR__);
    }

    /**
     * {@inheritdoc}
     */
    public function fetch()
    {
        $data = $this->memcached->fetch($this->key);

        return is_array($data) ? $data : array();
    }

    /**
     * {@inheritdoc}
     */
    public function save(array $data)
    {
        $this->memcached->save($this->key, $data);
    }
}
