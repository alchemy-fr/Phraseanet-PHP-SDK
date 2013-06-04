<?php

namespace PhraseanetSDK\HttpAdapter;

use PhraseanetSDK\Exception\BadResponseException;
use PhraseanetSDK\Exception\RuntimeException;

interface HttpAdapterInterface
{
    /**
     * Executes a GET request
     *
     * @param  string               $path The path to query
     * @param  array                $args An array of query parameters
     * @return string               The response body
     * @throws BadResponseException
     * @throws RuntimeException
     */
    public function get($path, array $args = array());

    /**
     * Executes a POST request
     *
     * @param  string               $path The path to query
     * @param  array                $args An array of query parameters
     *
     * @return string               The response body
     *
     * @throws BadResponseException
     * @throws RuntimeException
     */
    public function post($path, array $args = array());

    /**
     * Returns the encapsulated adapter
     */
    public function getAdapter();
}
