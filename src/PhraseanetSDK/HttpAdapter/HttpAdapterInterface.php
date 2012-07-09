<?php

namespace PhraseanetSDK\HttpAdapter;

interface HttpAdapterInterface
{
    public function getBaseUrl();

    public function setBaseUrl($url);

    public function get($path, array $args = array());

    public function post($path, array $args = array());
}
