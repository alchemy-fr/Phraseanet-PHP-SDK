<?php

namespace PhraseanetSDK;

use Doctrine\Common\Cache\Cache;
use Guzzle\Http\Client as GuzzleClient;
use Guzzle\Http\Curl\CurlException;
use Guzzle\Http\Exception\BadResponseException as GuzzleBadresponse;
use Guzzle\Common\Event;
use Guzzle\Common\Cache\DoctrineCacheAdapter;
use Monolog\Logger;
use PhraseanetSDK\Exception\ApiResponseException;
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
class Client extends ClientAbstract
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
     * A Guzzle Client which handleHTTP requests to the Phraseanet API
     *
     * @see http://guzzlephp.org for more informations
     * @var GuzzleClient
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
     * Api access token
     *
     * @var string
     */
    protected $accessToken;

    /**
     * To create an API key/secret pair, go to your account adminstation panel
     * in your phraseanet application.
     *
     * @param string $apiKey
     * @param string $apiSecret
     * @param CurlWrapper $curl
     */
    public function __construct($apiKey, $apiSecret, GuzzleClient $clientHttp, Logger $logger)
    {
        $this->httpClient = $clientHttp;
        $this->logger = $logger;

        $baseUrl = rtrim($this->httpClient->getBaseUrl(), '/');
        $this->httpClient->setBaseUrl($baseUrl . '/api/v1');

        $this->oauthAuthorizeEndpointUrl = sprintf('%s%s', $baseUrl, self::AUTH_ENDPOINT);
        $this->oauthTokenEndpointUrl = sprintf('%s%s', $baseUrl, self::TOKEN_ENDPOINT);

        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
    }

    /**
     * @Override this method to run the client
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @Override this method to run the client
     */
    public function setAccessToken($token)
    {
        $this->accessToken = $token;

        return $this;
    }

    /**
     * Return the HTTP client
     *
     * @return GuzzleClient
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }

    /**
     * Set the HTTP Client
     *
     * @param GuzzleClient $client
     * @return Client
     */
    public function setHttpClient(GuzzleClient $client)
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
     * @param string $type the API grant type
     * @param array $info info associated to the choosen grant type
     * @param Request $request The request associated with this authorization
     *
     * @return Client
     * @throws InvalidArgumentException if bad grant type provided
     */
    public function setGrantType($type, Array $info = null, Request $request = null)
    {
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
        $this->grantInfo = $info;

        return $this;
    }

    public function getGrantType()
    {
        return $this->grantType;
    }

    public function getGrantInformations()
    {
        return $this->grantInfo;
    }

    /**
     * Build the Authorisation Url
     *
     * @param array $scope the requested scope
     * @return string the authorization url
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
     * @return void
     *
     * @throws AuthenticationException if error occurs during authentication
     * @throws TransportException if problem occurs with transport layer
     */
    public function retrieveAccessToken(Request $request)
    {
        $token = $this->getAccessToken();

        try {
            /**
             * @todo throw an exception if something goes wrong
             */
            if ($this->grantType === self::GRANT_TYPE_AUTHORIZATION && null === $token) {

                if ($request->get('error')) {
                    throw new AuthenticationException($request->get('error'));
                }

                if (null === $request->get('code')) {
                    throw new AuthenticationException('Invalid authentication code');
                }

                $args = array(
                    'grant_type'    => 'authorization_code',
                    'client_id'     => $this->apiKey,
                    'client_secret' => $this->apiSecret,
                    'scope'         => $this->grantInfo['scope'],
                    'code'          => $request->get('code'),
                    'redirect_uri'  => $this->grantInfo['redirect_uri'],
                );

                $request = $this->httpClient
                    ->post($this->oauthTokenEndpointUrl)
                    ->addPostFields($args);

                $response = $request->send();

                $token = json_decode($response->getBody(), true);

                $this->setAccessToken($token["access_token"]);
            }
        } catch (CurlException $e) {
            throw new TransportException(
                $e->getMessage()
                , $e->getCode()
                , $e
            );
        }

        return;
    }

    /**
     *
     * Destroy stored token
     *
     * @return PhraseanetClientApi
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
     * @param string $path remote path
     * @param array $args request parameters
     * @param string $http_method http method
     * @return PhraseanetApiResponse
     *
     * @throws BadRequestException if error occurs with phraseanet API
     * @throws TransportException if problem occurs with transport layer
     */
    public function call($path, $args = array(), $http_method = 'POST', $throwException = true)
    {
        $queryDatas = array();

        if ( ! empty($args)) {
            $queryDatas['data'] = $args;
        }

        if ($this->getAccessToken()) {
            $queryDatas['oauth_token'] = $this->getAccessToken();
        }

        $template = '{?' . (null !== $this->getAccessToken() ? 'oauth_token,' : '') . ( ! empty($args) ? 'data*' : '' ) . '}';

        $path = sprintf('%s%s', ltrim($path, '/'), $template);

        if ( ! $throwException) {
            $this->httpClient->getEventDispatcher()->addListener('request.error', function(Event $event) {
                    $event->stopPropagation();
                }, -254
            );
        }

        try {
            switch (strtoupper($http_method)) {
                case 'POST' :

                    $start = microtime(true);
                    $request = $this->httpClient->post(array($path, $queryDatas));
                    $request->setHeader('Accept', 'application/json');
                    $response = $request->send();
                    $stop = microtime(true);
                    $this->logger->addInfo(sprintf('Request to Phraseanet API %s s. - %s', $path, round($stop - $start, 6)));

                    break;
                case 'GET' :
                    $start = microtime(true);
                    $request = $this->httpClient->get(array($path, $queryDatas));
                    $request->setHeader('Accept', 'application/json');
                    $response = $request->send();
                    $stop = microtime(true);
                    $this->logger->addInfo(sprintf('Request to Phraseanet API %s s. - %s', $path, round($stop - $start, 6)));

                    break;
                default :
                    throw new BadRequestException(sprintf(
                            'Phraseanet API do not support %s method'
                            , $http_method
                        )
                    );
                    break;
            }
        } catch (GuzzleBadresponse $e) {
            throw new BadResponseException(
                $e->getMessage()
                , $e->getCode()
                , $e
            );
        } catch (CurlException $e) {
            throw new TransportException(
                $e->getMessage()
                , $e->getCode()
                , $e
            );
        }

        return new Response(json_decode($response->getBody()));
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

}

