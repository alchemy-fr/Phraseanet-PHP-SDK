<?php

/*
 * This file is part of Phraseanet SDK.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhraseanetSDK\Http;

class ConnectedGuzzleAdapter implements GuzzleAdapterInterface
{
    /** @var GuzzleAdapterInterface */
    private $adapter;
    private $token;

    public function __construct($token, GuzzleAdapterInterface $adapter)
    {
        $this->token = $token;
        $this->adapter = $adapter;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    public function getGuzzle()
    {
        return $this->adapter->getGuzzle();
    }

    public function call(
        $method,
        $path,
        array $query = array(),
        array $postFields = array(),
        array $files = array(),
        array $headers = array()
    ) {
        $query = array_replace($query, array(
            'oauth_token' => $this->token,
        ));

        return $this->adapter->call($method, $path, $query, $postFields, $files, $headers);
    }
}
