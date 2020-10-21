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

use PhraseanetSDK\ApplicationInterface;
use Psr\Http\Message\RequestInterface;
use Guzzle\Http\Message\EntityEnclosingRequestInterface;

/**
 * todo : rewrite using middleware
 * possibly use existing "history" middleware ?
 */

class RequestExtractor
{
    public function extract(RequestInterface $request)
    {
        $postFields = $request instanceof EntityEnclosingRequestInterface ?
            $request->getPostFields()->toArray() :
            array();

        return array(
            'query'       => $request->getQuery()->toArray(),
            'post-fields' => $postFields,
            'method'      => $request->getMethod(),
            'path'        => substr($request->getPath(), strlen(ApplicationInterface::API_MOUNT_POINT)),
        );
    }
}
