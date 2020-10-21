<?php

namespace PhraseanetSDK\Http;

use GuzzleHttp\ClientInterface;

interface GuzzleAdapterInterface
{
    /**
     * @return ClientInterface
     */
    public function getGuzzle();

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
    public function call($method, $path, array $query = [], array $postFields = [], array $files = [], array $headers = []);
}
