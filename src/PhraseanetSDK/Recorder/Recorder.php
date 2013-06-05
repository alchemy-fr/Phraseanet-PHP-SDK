<?php

/*
 * This file is part of Phraseanet SDK.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhraseanetSDK\Recorder;

use Guzzle\Plugin\History\HistoryPlugin;
use PhraseanetSDK\Recorder\Storage\StorageInterface;

class Recorder
{
    private $plugin;
    private $storage;
    private $limit;
    private $serializer;

    public function __construct(HistoryPlugin $plugin, StorageInterface $storage, RequestSerializer $serializer, $limit = 400)
    {
        $this->plugin = $plugin;
        $this->storage = $storage;
        $this->limit = $limit;
        $this->serializer = $serializer;
    }

    public function getPlugin()
    {
        return $this->plugin;
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function getStorage()
    {
        return $this->storage;
    }

    public function save()
    {
        $stack = $this->storage->fetch();

        foreach ($this->plugin->getIterator() as $request) {
            $data = $this->serializer->serialize($request);
            $stack[] = $data;
        }
        $this->removeDuplicates($stack);
        $this->removeOldest($stack);

        $this->storage->save($stack);
    }

    private function removeOldest(&$stack)
    {
        while (count($stack) > $this->limit) {
            array_shift($stack);
        }
    }

    private function removeDuplicates(&$stack)
    {
        $knowns = array();
        $output = array();

        foreach (array_reverse($stack) as $key => $data) {
            $md5 = md5(serialize($data));
            if (!isset($knowns[$md5])) {
                array_unshift($output, $data);
                $knowns[$md5] = true;
            }
        }

        $stack = $output;
    }
}
