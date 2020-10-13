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

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Guzzle\Plugin\Cache\DefaultCanCacheStrategy;

class CanCacheStrategy extends DefaultCanCacheStrategy
{
    /**
     * {@inheritdoc}
     */
    public function canCacheRequest(RequestInterface $request)
    {
        return parent::canCacheRequest($request);
    }

    /**
     * {@inheritdoc}
     */
    public function canCacheResponse(ResponseInterface $response)
    {
        if (false !== strpos($response->getEffectiveUrl(), '/api/v1/monitor/')) {
            return false;
        }

        return parent::canCacheResponse($response);
    }
}
