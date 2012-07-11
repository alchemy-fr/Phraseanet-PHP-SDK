<?php

namespace PhraseanetSDK\HttpAdapter;

use Monolog\Logger;

interface HttpAdapterInterface
{
    public function getBaseUrl();

    public function setBaseUrl($url);

    public function get($path, array $args = array());

    public function post($path, array $args = array());

    public function setLogger(Logger $logger = null);
}
