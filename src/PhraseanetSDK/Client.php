<?php

namespace PhraseanetSDK;

use Monolog\Logger;
use PhraseanetSDK\Authentication\StoreInterface;
use PhraseanetSDK\Authentication\DefaultStore;
use PhraseanetSDK\HttpAdapter\HttpAdapterInterface;
use PhraseanetSDK\Exception\AuthenticationException;
use PhraseanetSDK\Exception\BadRequestException;
use PhraseanetSDK\Exception\BadResponseException;
use PhraseanetSDK\Exception\RuntimeException;
use PhraseanetSDK\Exception\TransportException;
use PhraseanetSDK\Exception\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;

/**
 *
 * Phraseanet Client, perform the HTTP requests against Phraseanet API
 *
 */
class Client extends AbstractClient
{
    /**
     * Phraseanet API Endpoint
     * @var string
     */
    const TOKEN_ENDPOINT = '/api/oauthv2/token';
    const AUTH_ENDPOINT = '/api/oauthv2/authorize';

    /**
     * Oauth grant type
     */
    const GRANT_TYPE_AUTHORIZATION = 'authorization_code';

    /**
     * The OAuth authorization server endpoint URL
     */
    protected $oauthAuthorizeEndpointUrl = '';

    /**
     * The OAuth token server endpoint URL
     */
    protected $oauthTokenEndpointUrl = '';

    /**
     * @var HttpAdapterInterface
     */
    protected $httpClient;

    /**
     * Api Key
     *
     * @var string
     */
    protected $apiKey;

    /**
     * Api Secret
     *
     * @var string
     */
    protected $apiSecret;

    /**
     * Grant type
     *
     * @var string
     */
    protected $grantType;

    /**
     * Infos associated to the grant type
     *
     * @var array
     */
    protected $grantInfo;

    /**
     *
     * @var StoreInterface
     */
    protected $tokenStore;

    /**
     * To create an API key/secret pair, go to your account adminstation panel
     * in your phraseanet application.
     *
     * @param string               $apiKey     Your API key
     * @param string               $apiSecret  Your API secret
     * @param HttpAdapterInterface $clientHttp An HTTP Client
     * @param Logger               $logger     A logger
     */
    public function __construct($apiKey, $apiSecret, HttpAdapterInterface $clientHttp, Logger $logger = null)
    {
        $this->httpClient = $clientHttp;
        $this->logger = $logger;

        $baseUrl = rtrim($this->httpClient->getBaseUrl(), '/');

        $this->httpClient->setBaseUrl($baseUrl . '/api/v1');
        $this->httpClient->setLogger($logger);

        $this->oauthAuthorizeEndpointUrl = sprintf('%s%s', $baseUrl, self::AUTH_ENDPOINT);
        $this->oauthTokenEndpointUrl = sprintf('%s%s', $baseUrl, self::TOKEN_ENDPOINT);

        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
        $this->tokenStore = new DefaultStore();
    }

    /**
     * Set the token store
     *
     * @param StoreInterface $store
     * @return Client
     */
    final public function setTokenStore(StoreInterface $store)
    {
        $this->tokenStore = $store;

        return $this;
    }

    /**
     * Get the access token
     *
     * @return string
     */
    final public function getAccessToken()
    {
        return $this->tokenStore->getToken();
    }

    /**
     * Set the access token
     *
     * @param string $token
     * @return Client
     */
    final public function setAccessToken($token)
    {
        $this->tokenStore->saveToken($token);

        return $this;
    }

    /**
     * Return the HTTP client
     *
     * @return HttpAdapterInterface
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }

    /**
     * Set the HTTP Client Adapter
     *
     * @param  HttpAdapter\HttpAdapterInterface $client
     * @return Client
     */
    public function setHttpClient(HttpAdapter\HttpAdapterInterface $client)
    {
        $this->httpClient = $client;

        return $this;
    }

    /**
     * Change the default grant type.
     *
     * !! Only Client::GRANT_TYPE_AUTHORIZATION is currently supported !!
     *
     * Info Keys:
     * - redirect_uri: if $type is Client::GRANT_TYPE_AUTHORIZATION, this key can be provided. If omited,
     *                 the current URL will be used. Make sure this value have to stay the same before
     *                 the user is redirect to the authorization page and after the authorization page
     *                 redirected to this provided URI (the token server will change this).
     *
     * @param string  $type    the API grant type
     * @param array   $info    info associated to the choosen grant type
     * @param Request $request The request associated with this authorization
     *
     * @return Client
     * @throws InvalidArgumentException if bad grant type provided
     */
    public function setGrantType($type, Array $info = null, Request $request = null)
    {
        $defaultInfos = array('redirect_uri' => '', 'scope'        => '');

        switch ($type) {
            case self::GRANT_TYPE_AUTHORIZATION:

                if ( ! isset($info['redirect_uri']) && $request) {
                    $info['redirect_uri'] = $this->getUrlWithoutOauth2Parameters($request);
                }

                if ( ! isset($info['scope'])) {
                    $info['scope'] = '';
                }
                break;
            default:
                throw new InvalidArgumentException(sprintf(
                        'Only %s grant type is currently supported'
                        , self::GRANT_TYPE_AUTHORIZATION
                    )
                );
        }

        $this->grantType = $type;
        $this->grantInfo = array_merge($defaultInfos, $info);

        return $this;
    }

    /**
     * Return the curreznt grant type
     *
     * @return string
     */
    public function getGrantType()
    {
        return $this->grantType;
    }

    /**
     * Return grant informations as array
     *
     * @return array
     */
    public function getGrantInformations()
    {
        return $this->grantInfo;
    }

    /**
     * Build the Authorisation Url
     *
     * @param  array            $scope the requested scope
     * @return string           the authorization url
     * @throws RuntimeException if bad grant type provided
     */
    public function getAuthorizationUrl(Array $scope = array())
    {
        if ($this->grantType !== self::GRANT_TYPE_AUTHORIZATION) {
            throw new RuntimeException('This method can only be used with TOKEN grant type.');
        }

        $oauthParams = array(
            'response_type' => 'code'
            , 'client_id'     => $this->apiKey
            , 'redirect_uri'  => $this->grantInfo['redirect_uri']
            , 'scope'         => implode(' ', $scope)
        );

        $url = http_build_query($oauthParams, null, '&');

        return sprintf('%s?%s', $this->oauthAuthorizeEndpointUrl, $url);
    }

    /**
     *
     * Retrieve your access Token from your callback endpoint
     *
     * @throws AuthenticationException if error occurs during authentication
     * @throws TransportException      if problem occurs with transport layer
     */
    public function retrieveAccessToken(Request $request)
    {
        $token = $this->getAccessToken();

        if ($this->grantType === self::GRANT_TYPE_AUTHORIZATION && null === $token) {

            if ($request->get('error')) {
                throw new AuthenticationException($request->get('error'));
            }

            if (null === $request->get('code')) {
                return;
            }

            $args = array(
                'grant_type'    => 'authorization_code',
                'client_id'     => $this->apiKey,
                'client_secret' => $this->apiSecret,
                'scope'         => $this->grantInfo['scope'],
                'code'          => $request->get('code'),
                'redirect_uri'  => $this->grantInfo['redirect_uri'],
            );

            try {
                $responseContent = $this->httpClient->post($this->oauthTokenEndpointUrl, $args);
                $token = json_decode($responseContent, true);
                $this->setAccessToken($token["access_token"]);
            } catch (BadResponseException $e) {
                $response = json_decode($e->getResponseBody(), true);
                $msg = isset($response['error']) ? $response['error'] : (isset($response['msg']) ? $response['msg'] : '');
                throw new AuthenticationException($msg);
            }
        }

        return;
    }

    /**
     *
     * Destroy stored token
     *
     * @return Client
     */
    public function logout()
    {
        $this->setAccessToken(null);

        return $this;
    }

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
    public function call($path, $args = array(), $httpMethod = 'POST')
    {
        $responseContent = null;

        $args['oauth_token'] = $this->getAccessToken();

        switch (strtoupper($httpMethod)) {
            case 'POST' :
                $start = microtime(true);
                $responseContent = $this->httpClient->post($path, $args);
                $stop = microtime(true);
                $this->log(sprintf('Request to Phraseanet API %s s. - %s', $path, round($stop - $start, 6)));
                break;
            case 'GET' :
                $start = microtime(true);
                $responseContent = $this->httpClient->get($path, $args);
                $stop = microtime(true);
                $this->log(sprintf('Request to Phraseanet API %s s. - %s', $path, round($stop - $start, 6)));
                break;
            default :
                throw new BadRequestException(sprintf('Phraseanet API do not support %s method', $httpMethod));
        }

        if (null === $json = json_decode($responseContent)) {
            throw new RuntimeException('Json response cannot be decoded or the encoded data is deeper than the recursion limit');
        }

        return new Response($json);
    }

    /**
     * Returns the current URL, removing of known OAuth parameters that should not persist.
     *
     * @return String the current URL
     */
    protected function getUrlWithoutOauth2Parameters(Request $request)
    {
        $toReAdd = array();

        foreach ($request->query->all() as $key => $value) {
            if ( ! in_array($key, array('code', 'scope', 'error', 'error_description'))) {
                continue;
            }

            $toReAdd[$key] = $value;
            $request->query->remove($key);
        }

        $ret = $request->getUri();

        foreach ($toReAdd as $key => $value) {
            $request->query->set($key, $value);
        }

        return $ret;
    }

    /**
     * Log a message
     *
     * @param string $message
     */
    private function log($message)
    {
        if (null !== $this->logger) {
            $this->logger->addInfo($message);
        }
    }
}
