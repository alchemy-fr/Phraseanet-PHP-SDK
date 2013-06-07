<?php

namespace PhraseanetSDK\Http;

interface GuzzleAdapterInterface
{
    public function getGuzzle();

    public function call($method, $path, array $query = array(), array $postFields = array());
}
