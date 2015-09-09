<?php

namespace PhraseanetSDK\Http;

use Guzzle\Http\ClientInterface;

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
    public function call(
        $method,
        $path,
        array $query = array(),
        array $postFields = array(),
        array $files = array(),
        array $headers = array()
    );
}
