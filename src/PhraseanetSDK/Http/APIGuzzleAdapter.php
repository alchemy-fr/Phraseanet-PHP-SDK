<?php

namespace PhraseanetSDK\Http;

use PhraseanetSDK\Exception\RuntimeException;

class APIGuzzleAdapter implements GuzzleAdapterInterface
{
    /** @var GuzzleAdapterInterface */
    private $adapter;

    public function __construct(GuzzleAdapterInterface $adapter)
    {
        $this->adapter = $adapter;
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
        $json = @json_decode($this->adapter->call($method, $path, $query, $postFields, $files, $headers));

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new RuntimeException(
                'Json response cannot be decoded or the encoded data is deeper than the recursion limit'
            );
        }

        return new APIResponse($json);
    }
}
