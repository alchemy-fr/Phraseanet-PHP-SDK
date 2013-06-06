<?php

/*
 * This file is part of Phraseanet SDK.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhraseanetSDK;

use PhraseanetSDK\Exception\AuthenticationException;
use PhraseanetSDK\Exception\BadRequestException;
use PhraseanetSDK\Exception\BadResponseException;
use PhraseanetSDK\HttpAdapter\Response;
use PhraseanetSDK\Exception\RuntimeException;
use PhraseanetSDK\Exception\TransportException;
use Symfony\Component\HttpFoundation\Request;

interface ClientInterface
{
    /**
     * Phraseanet API Endpoints
     */
    const TOKEN_ENDPOINT = '/api/oauthv2/token';
    const AUTH_ENDPOINT = '/api/oauthv2/authorize';
    const API_MOUNT_POINT = '/api/v1/';

    /**
     * Oauth grant type
     */
    const GRANT_TYPE_AUTHORIZATION = 'authorization_code';

    /**
     * Get the access token
     *
     * @return string
     */
    public function getAccessToken();

    /**
     * Set the access token
     *
     * @param string $token
     *
     * @return ClientInterface
     */
    public function setAccessToken($token);

    /**
     *
     * Retrieve your access Token from your callback endpoint
     *
     * @throws AuthenticationException if error occurs during authentication
     * @throws TransportException      if problem occurs with transport layer
     */
    public function retrieveAccessToken(Request $request);

    /**
     * Destroy stored token
     *
     * @return Client
     */
    public function logout();

    /**
     * Returns the entity manager
     *
     * @return EntityManager
     */
    public function getEntityManager();

    /**
     * Call a remote Phraseanet API method
     *
     * @param  string   $method     http method
     * @param  string   $path       remote path
     * @param  array    $query      query parameters
     * @param  array    $postFields post fields parameters
     * @return Response
     *
     * @throws BadRequestException  if method is unsupported phraseanet API
     * @throws BadResponseException if response is 4xx or 5xx
     * @throws RuntimeException     if problem occurs
     */
    public function call($method, $path, $query = array(), $postFields = array());
}
