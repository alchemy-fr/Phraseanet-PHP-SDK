<?php

/*
 * This file is part of Phraseanet-PHP-SDK.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhraseanetSDK\Http\GuzzleHttp;

use GuzzleHttp\ClientInterface;
use PhraseanetSDK\Http\Client;
use PhraseanetSDK\Http\Endpoint;

class GuzzleHttpClient implements Client
{

    public static function create(Endpoint $endpoint)
    {
        $client = new \GuzzleHttp\Client([
            'base_url' => $endpoint->getUrl()
        ]);

        return new self($client, $endpoint);
    }

    /**
     * @var \GuzzleHttp\Client
     */
    private $client;

    /**
     * @var Endpoint
     */
    private $endpoint;

    /**
     * @param ClientInterface $client
     * @param Endpoint $endpoint
     */
    public function __construct(ClientInterface $client, Endpoint $endpoint)
    {
        $this->client = $client;
        $this->endpoint = $endpoint;
    }

    /**
     * @return Endpoint
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
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

    }
}
