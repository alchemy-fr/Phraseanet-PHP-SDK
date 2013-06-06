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

use PhraseanetSDK\ClientInterface;
use Guzzle\Http\Message\RequestInterface;
use Guzzle\Http\Message\EntityEnclosingRequestInterface;

class RequestExtractor
{
    public function extract(RequestInterface $request)
    {
        return array(
            'query'       => $request->getQuery()->toArray(),
            'post-fields' => $request instanceof EntityEnclosingRequestInterface ? $request->getPostFields()->toArray() : array(),
            'method'      => $request->getMethod(),
            'path'        => substr($request->getPath(), strlen(ClientInterface::API_MOUNT_POINT)),
        );
    }
}
