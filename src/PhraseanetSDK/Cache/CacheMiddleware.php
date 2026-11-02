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

use GuzzleHttp\Promise\Create;
use GuzzleHttp\Psr7\Response;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Caches the successful GET responses, without revalidation, until the TTL expires.
 *
 * The key covers the whole URL, and therefore the token: a response is never
 * shared between two tokens.
 */
class CacheMiddleware
{
    private const UNCACHEABLE_PATH = '/api/v1/monitor/';

    public function __construct(private CacheItemPoolInterface $pool, private int $ttl)
    {
    }

    public function __invoke(callable $handler): callable
    {
        return function (RequestInterface $request, array $options) use ($handler) {
            if (!$this->canCacheRequest($request)) {
                return $handler($request, $options);
            }

            $item = $this->pool->getItem(hash('sha256', $request->getUri().'|'.$request->getHeaderLine('Accept')));

            if ($item->isHit()) {
                return Create::promiseFor(new Response(200, $item->get()['headers'], $item->get()['body']));
            }

            return $handler($request, $options)->then(function (ResponseInterface $response) use ($item) {
                if ($this->canCacheResponse($response)) {
                    $body = (string) $response->getBody();
                    $response->getBody()->rewind();
                    $item->set(['headers' => $response->getHeaders(), 'body' => $body])->expiresAfter($this->ttl);
                    $this->pool->save($item);
                }

                return $response;
            });
        };
    }

    private function canCacheRequest(RequestInterface $request): bool
    {
        return $request->getMethod() === 'GET'
            && !str_contains($request->getUri()->getPath(), self::UNCACHEABLE_PATH)
            && !str_contains($request->getHeaderLine('Cache-Control'), 'no-store');
    }

    private function canCacheResponse(ResponseInterface $response): bool
    {
        return $response->getStatusCode() === 200
            && !str_contains($response->getHeaderLine('Cache-Control'), 'no-store');
    }
}
