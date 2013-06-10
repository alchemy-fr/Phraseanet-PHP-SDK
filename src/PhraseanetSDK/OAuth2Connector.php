<?php

namespace PhraseanetSDK;

use PhraseanetSDK\Http\GuzzleAdapter;
use PhraseanetSDK\Exception\AuthenticationException;
use PhraseanetSDK\Exception\BadResponseException;
use PhraseanetSDK\Exception\TransportException;

class OAuth2Connector
{
    const TOKEN_ENDPOINT = '/api/oauthv2/token';
    const AUTH_ENDPOINT = '/api/oauthv2/authorize';

    /**
     * Oauth authorization grant type
     */
    const GRANT_TYPE_AUTHORIZATION = 'authorization_code';

    private $adapter;
    private $clientId;
    private $secret;

    public function __construct(GuzzleAdapter $adapter, $clientId, $secret)
    {
        $this->adapter = $adapter;
        $this->clientId = $clientId;
        $this->secret = $secret;
    }

    private function getUrl()
    {
        $baseUrl = $this->adapter->getBaseUrl();

        return substr($baseUrl, 0, strlen($baseUrl) - 8);
    }

    /**
     * Builds the Authorization Url
     *
     * @param string $redirectUri
     * @param array  $parameters
     * @param array  $scopes
     *
     * @return string
     */
    public function getAuthorizationUrl($redirectUri, array $parameters = array(), array $scopes = array())
    {
        $oauthParams = array_replace($parameters, array(
            'redirect_uri'  => $redirectUri,
            'response_type' => 'code',
            'client_id'     => $this->clientId,
            'scope'         => implode(' ', $scopes),
        ));

        $parameters = http_build_query($oauthParams, null, '&');

        return sprintf('%s%s?%s', $this->getUrl(), static::AUTH_ENDPOINT, $parameters);
    }

    /**
     * Retrieves your access Token from your callback endpoint
     *
     * @return Client
     *
     * @throws AuthenticationException if error occurs during authentication
     * @throws TransportException      if problem occurs with transport layer
     */
    public function retrieveAccessToken($code, $redirectUri)
    {
        $postFields = array(
            'grant_type'    => static::GRANT_TYPE_AUTHORIZATION,
            'redirect_uri'  => $redirectUri,
            'client_id'     => $this->clientId,
            'client_secret' => $this->secret,
            'code'          => $code,
        );

        try {
            $responseContent = $this->adapter->call('POST', $this->getUrl().static::TOKEN_ENDPOINT, array(), $postFields);
            $data = json_decode($responseContent, true);
            $token = $data["access_token"];
        } catch (BadResponseException $e) {
            $response = json_decode($e->getResponseBody(), true);
            $msg = isset($response['error']) ? $response['error'] : (isset($response['msg']) ? $response['msg'] : '');

            throw new AuthenticationException($msg);
        }

        return $token;
    }
}
