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
use PhraseanetSDK\Recorder\Filters\FilterInterface;

class Recorder
{
    private $plugin;
    private $storage;
    private $extractor;
    private $filters = array();

    public function __construct(HistoryPlugin $plugin, StorageInterface $storage, RequestExtractor $extractor)
    {
        $this->plugin = $plugin;
        $this->storage = $storage;
        $this->extractor = $extractor;
    }

    public function addFilter(FilterInterface $filter)
    {
        $this->filters[] = $filter;

        return $this;
    }

    public function getPlugin()
    {
        return $this->plugin;
    }

    public function getStorage()
    {
        return $this->storage;
    }

    public function save()
    {
        $stack = $this->storage->fetch();

        foreach ($this->plugin->getIterator() as $request) {
            $data = $this->extractor->extract($request);
            $stack[] = $data;
        }

        $this->applyFilters($stack);
        $this->storage->save($stack);
    }

    private function applyFilters(&$stack)
    {
        foreach ($this->filters as $filter) {
            $filter->apply($stack);
        }
    }
}
