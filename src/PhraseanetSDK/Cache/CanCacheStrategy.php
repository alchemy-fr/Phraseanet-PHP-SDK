<?php

namespace PhraseanetSDK\Cache;

use Guzzle\Http\Message\RequestInterface;
use Guzzle\Http\Message\Response;
use Guzzle\Plugin\Cache\CanCacheStrategyInterface;

class CanCacheStrategy implements CanCacheStrategyInterface
{
    /**
     * {@inheritdoc}
     */
    public function canCacheRequest(RequestInterface $request)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function canCacheResponse(Response $response)
    {
        return $response->isSuccessful();
    }
}

