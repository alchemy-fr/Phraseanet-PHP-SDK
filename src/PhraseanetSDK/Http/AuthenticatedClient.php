<?php

/*
 * This file is part of Phraseanet-PHP-SDK.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhraseanetSDK\Http;

class AuthenticatedClient implements Client
{
    /**
     * @var string
     */
    private $token;

    /**
     * @var Client
     */
    private $client;

    /**
     * @param Client $client
     * @param string $token
     */
    public function __construct(Client $client, $token)
    {
        $this->client = $client;
        $this->token = $token;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     *
     * @param string $method
     * @param string $path
     * @param array $query
     * @param array $postFields
     * @param array $files
     * @param array $headers
     * @return string
     */
    public function call(
        $method,
        $path,
        array $query = array(),
        array $postFields = array(),
        array $files = array(),
        array $headers = array()
    ) {
        $query = array_replace($query, [ 'oauth_token' => $this->token ]);

        return $this->client->call($method, $path, $query, $postFields, $files, $headers);
    }
}
