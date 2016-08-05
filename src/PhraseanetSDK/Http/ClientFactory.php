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

class ClientFactory
{

    public function createClient()
    {
        if (interface_exists('GuzzleHttp\ClientInterface')) {
            return $this->createGuzzleClient();
        }

        if (interface_exists('Guzzle\Http\ClientInterface')) {
            return $this->createLegacyGuzzleClient();
        }

        throw new \RuntimeException('No HTTP adapter is available.');
    }

    /**
     * @return \GuzzleHttp\ClientInterface|Client
     */
    private function createGuzzleClient()
    {
        $client = new Client([]);

        return new
    }

    /**
     * @return \Guzzle\Http\Client|Client
     */
    private function createLegacyGuzzleClient()
    {

    }
}
