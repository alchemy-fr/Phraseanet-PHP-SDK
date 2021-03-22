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

interface Client 
{
    /**
     * @return Endpoint
     */
    public function getEndpoint();

    /**
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
