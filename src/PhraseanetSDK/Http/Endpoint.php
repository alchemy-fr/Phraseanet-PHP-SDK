<?php

namespace PhraseanetSDK\Http;

use PhraseanetSDK\ApplicationInterface;

class Endpoint
{

    /**
     * @var string
     */
    private $url;

    /**
     * @param string $url
     */
    public function __construct($url)
    {
        $this->url = $this->normalizeEndpoint($url);
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getUrl();
    }

    /**
     * @param $endpoint
     * @return string
     */
    private function normalizeEndpoint($endpoint)
    {
        $versionMountPoint = ApplicationInterface::API_MOUNT_POINT;

        // Test if URL already ends with API_MOUNT_POINT
        $mountPoint = substr(trim($endpoint, '/'), -strlen($versionMountPoint));

        if ($versionMountPoint !== $mountPoint) {
            $endpoint = sprintf('%s%s/', trim($endpoint, '/'), $versionMountPoint);
        }

        return $endpoint;
    }
}
