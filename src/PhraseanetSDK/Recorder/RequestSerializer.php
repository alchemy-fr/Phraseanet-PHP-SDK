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

use Guzzle\Http\Message\RequestInterface;
use Guzzle\Http\ClientInterface;
use Guzzle\Http\Message\EntityEnclosingRequestInterface;

class RequestSerializer
{
    public function serialize(RequestInterface $request)
    {
        return array(
            'query'   => $request->getQuery()->toArray(),
            'params'  => $request instanceof EntityEnclosingRequestInterface ? $request->getPostFields()->toArray() : array(),
            'method'  => $request->getMethod(),
            'path'    => $request->getPath(),
            'headers' => $request->getHeaders()->toArray(),
        );
    }

    public function unserialize(ClientInterface $client, array $data)
    {
        $request = $client->createRequest($data['method'], $data['path'], $data['headers']);

        if ($request instanceof EntityEnclosingRequestInterface) {
            $request->addPostFields($data['params']);
        }

        $request->getQuery()->replace($data['query']);

        return $request;
    }
}
