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

use Doctrine\Common\Cache\MemcacheCache;

class MemcacheStorage implements StorageInterface
{
    private $memcache;
    private $key;

    public function __construct(MemcacheCache $memcache)
    {
        $this->memcache = $memcache;
        $this->key = 'PhraseanetSDK-recording-'.md5(__DIR__);
    }

    /**
     * {@inheritdoc}
     */
    public function fetch()
    {
        $data = $this->memcache->fetch($this->key);

        return is_array($data) ? $data : array();
    }

    /**
     * {@inheritdoc}
     */
    public function save(array $data)
    {
        $this->memcache->save($this->key, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->fetch());
    }
}
