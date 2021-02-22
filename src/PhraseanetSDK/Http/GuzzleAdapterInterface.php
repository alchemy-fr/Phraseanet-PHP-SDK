<?php

namespace PhraseanetSDK\Http;

use GuzzleHttp\ClientInterface;

interface GuzzleAdapterInterface
{
    /**
     * @return ClientInterface
     */
    public function getGuzzle(): ClientInterface;

    /**
     *
     * @param string $method
     * @param string $path
     * @param array $query
     * @param array $postFields
     * @param array $files
     * @param array $headers
     * @return string|APIResponse
     */
    public function call(string $method, string $path, array $query = [], array $postFields = [], array $files = [], array $headers = []);
}
