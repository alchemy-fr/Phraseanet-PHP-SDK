<?php

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
     * @param  string $token
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
     *
     * Call a remote Phraseanet API method
     *
     * @param  string   $path       remote path
     * @param  array    $args       request parameters
     * @param  string   $httpMethod http method
     * @return Response
     *
     * @throws BadRequestException  if method is unsupported phraseanet API
     * @throws BadResponseException if response is 4xx or 5xx
     * @throws RuntimeException     if problem occurs
     */
    public function call($path, $args = array(), $httpMethod = 'POST');
}
