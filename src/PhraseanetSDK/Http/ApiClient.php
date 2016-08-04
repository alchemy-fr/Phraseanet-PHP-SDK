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

use PhraseanetSDK\Exception\RuntimeException;

class ApiClient
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var bool
     */
    private $extendedMode = false;

    /**
     * @param Client $client
     * @param bool $extendedMode
     */
    public function __construct(Client $client, $extendedMode = false)
    {
        $this->client = $client;
        $this->extendedMode = (bool) $extendedMode;
    }

    /**
     * Enables extended API responses
     */
    public function enableExtendedMode()
    {
        $this->extendedMode = true;
    }

    /**
     * Disables extended API responses
     */
    public function disableExtendedMode()
    {
        $this->extendedMode = false;
    }

    /**
     * @return bool
     */
    public function isExtendedModeEnabled()
    {
        return $this->extendedMode;
    }

    /**
     * @return string
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param string $method
     * @param string $path
     * @param array $query
     * @param array $postFields
     * @param array $files
     * @param array $headers
     * @return ApiResponse
     */
    public function call(
        $method,
        $path,
        array $query = array(),
        array $postFields = array(),
        array $files = array(),
        array $headers = array()
    ) {
        $headers = array_replace($headers, [ 'Accept' => $this->getAcceptHeader() ]);
        $responseBody = $this->client->call($method, $path, $query, $postFields, $files, $headers);

        $decodedResponse = @json_decode($responseBody);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new RuntimeException(
                'Json response cannot be decoded or the encoded data is deeper than the recursion limit'
            );
        }

        return new ApiResponse($decodedResponse);
    }

    /**
     * @return string
     */
    private function getAcceptHeader()
    {
        return $this->isExtendedModeEnabled() ?
            'application/vnd.phraseanet.record-extended+json' :
            'application/json';
    }
}
