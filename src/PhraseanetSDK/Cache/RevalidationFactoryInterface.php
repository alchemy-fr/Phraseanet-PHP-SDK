<?php

namespace PhraseanetSDK\Cache;

use Guzzle\Plugin\Cache\RevalidationInterface;
use PhraseanetSDK\Exception\RuntimeException;

interface RevalidationFactoryInterface
{
    /**
     * Creates a RevalidationInterface
     *
     * @param string $type
     *
     * @return RevalidationInterface
     *
     * @throws RuntimeException
     */
    public function create($type);
}
