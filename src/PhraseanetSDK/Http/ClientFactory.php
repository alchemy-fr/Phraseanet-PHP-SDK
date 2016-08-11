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

use GuzzleHttp\Client;
use PhraseanetSDK\Http\Guzzle\GuzzleClient;
use PhraseanetSDK\Http\GuzzleHttp\GuzzleHttpClient;

class ClientFactory
{

    public function createClient(Endpoint $endpoint)
    {
        if (interface_exists('GuzzleHttp\ClientInterface')) {
            return $this->createGuzzleClient($endpoint);
        }

        if (interface_exists('Guzzle\Http\ClientInterface')) {
            return $this->createLegacyGuzzleClient($endpoint);
        }

        throw new \RuntimeException('No HTTP adapter is available.');
    }

    /**
     * @param Endpoint $endpoint
     * @return Client|\GuzzleHttp\ClientInterface
     */
    private function createGuzzleClient(Endpoint $endpoint)
    {
        return GuzzleClient::create($endpoint);
    }

    /**
     * @param $endpoint
     * @return \Guzzle\Http\Client|Client
     */
    private function createLegacyGuzzleClient(Endpoint $endpoint)
    {
        return GuzzleHttpClient::create($endpoint);
    }
}
