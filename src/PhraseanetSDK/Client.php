<?php

namespace PhraseanetSDK;

use Guzzle\Http\Client as GuzzleClient;
use Guzzle\Http\Curl\CurlException;
use Guzzle\Http\Exception\BadResponseException;
use Guzzle\Common\Event;
use Monolog\Logger;
use PhraseanetSDK\Exception;

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
     * The API endpoint URL
     */
    protected $apiEndpointUrl = '';

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
     * @see http://guzzlephp.org for more informations
     * @var GuzzleClient
     */
    protected $httpClient;

    /**
     * Api credentials
     * Info Keys :
     *   key: the api client key access
     *   secret: the api scret key access
     * @var array
     */
    protected $apiAccess;

    /**
     * Choosen grant type
     * @var string
     */
    protected $grantType;

    /**
     * Associated infos to the choosen grant type
     * @var array
     */
    protected $grantInfo;

    /**
     * Api access token
     * @var string
     */
    protected $accessToken;

    /**
     * To create an API key/secret pair, go to your account adminstation panel
     * in your phraseanet application.
     *
     * @param string $instanceUrl
     * @param string $apiKey
     * @param string $apiSecret
     * @param CurlWrapper $curl
     */
    public function __construct($instanceUrl, $apiKey, $apiSecret, GuzzleClient $clientHttp, Logger $logger)
    {
        if ( ! $this->isValidUrl($instanceUrl)) {
            throw new Exception\InvalidArgumentException(
                sprintf('%s is not a valid url', $instanceUrl)
            );
        }

        $url = rtrim($instanceUrl, '/');

        $this->httpClient = $clientHttp;
        $this->logger = $logger;

        $this->apiEndpointUrl = $this->httpClient->getBaseUrl();

        $this->oauthAuthorizeEndpointUrl = sprintf('%s%s', $url, self::AUTH_ENDPOINT);
        $this->oauthTokenEndpointUrl = sprintf('%s%s', $url, self::TOKEN_ENDPOINT);

        $this->url = $url;

        $this->apiAccess['key'] = $apiKey;
        $this->apiAccess['secret'] = $apiSecret;
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
     * @param string type the API grant type
     * @param $info array info associated to the chosen grant type
     * Info Keys:
     * - redirect_uri: if $type is Client::GRANT_TYPE_AUTHORIZATION, this key can be provided. If omited,
     *                 the current URL will be used. Make sure this value have to stay the same before
     *                 the user is redirect to the authorization page and after the authorization page
     *                 redirected to this provided URI (the token server will change this).
     *
     * @return Client
     * @throws InvalidArgumentException if bad grant type provided
     */
    public function setGrantType($type, Array $info = null)
    {
        switch ($type) {
            case self::GRANT_TYPE_AUTHORIZATION:
                if ( ! isset($info['redirect_uri'])) {
                    $info['redirect_uri'] = $this->getCurrentUrl();
                }

                if ( ! isset($info['scope'])) {
                    $info['scope'] = '';
                }
                break;
            default:
                throw new Exception\InvalidArgumentException(sprintf(
                        'Only %s grant type is currently supported'
                        , self::GRANT_TYPE_AUTHORIZATION
                    )
                );
        }
        $this->grantType = $type;
        $this->grantInfo = $info;

        return $this;
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
            throw new Exception\RuntimeException('This method can only be used with TOKEN grant type.');
        }

        $oauthParams = array(
            'response_type' => 'code'
            , 'client_id'     => $this->apiAccess['key']
            , 'redirect_uri'  => $this->grantInfo['redirect_uri']
            , 'scope'         => implode(' ', $scope)
        );

        $url = http_build_query($oauthParams, null, '&');

        return sprintf('%s?%s', $this->oauthAuthorizeEndpointUrl, $url);
    }

    /**
     *
     * Retrieve your access Token from your callback endpoint
     * Use $_GET globale variable
     *
     * @return void
     *
     * @throws Exception\AuthenticationException if error occurs during authentication
     * @throws Exception\TransportException if problem occurs with transport layer
     */
    public function retrieveAccessToken()
    {
        $token = $this->getAccessToken();

        try {
            if ($this->grantType === self::GRANT_TYPE_AUTHORIZATION && null === $token) {
                if (isset($_GET['code'])) {
                    $args = array(
                        'grant_type'    => 'authorization_code',
                        'client_id'     => $this->apiAccess['key'],
                        'client_secret' => $this->apiAccess['secret'],
                        'scope'         => $this->grantInfo['scope'],
                        'code'          => $_GET['code'],
                        'redirect_uri'  => $this->grantInfo['redirect_uri'],
                    );

                    $request = $this->httpClient
                        ->post($this->oauthTokenEndpointUrl)
                        ->addPostFields($args);

                    $response = $request->send();

                    $token = json_decode($response->getBody(), true);

                    $this->setAccessToken($token["access_token"]);
                } elseif (isset($_GET['error'])) {
                    throw new Exception\AuthenticationException($_GET['error']);
                }
            }
        } catch (CurlException $e) {
            throw new Exception\TransportException(
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
                    throw new Exception\BadRequestException(sprintf(
                            'Phraseanet API do not support %s method'
                            , $http_method
                        )
                    );
                    break;
            }
        } catch (BadResponseException $e) {
            throw new Exception\BadResponseException(
                $e->getMessage()
                , $e->getCode()
                , $e
            );
        } catch (CurlException $e) {
            throw new Exception\TransportException(
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
    protected function getCurrentUrl()
    {
        $secure = false;
        if (isset($_SERVER['HTTPS'])) {
            $secure = strtolower($_SERVER['HTTPS']) === 'on' || $_SERVER['HTTPS'] == 1;
        } elseif (isset($_SERVER['HTTP_SSL_HTTPS'])) {
            $secure = strtolower($_SERVER['HTTP_SSL_HTTPS']) === 'on' || $_SERVER['HTTP_SSL_HTTPS'] == 1;
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
            $secure = strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https';
        }
        $scheme = $secure ? 'https://' : 'http://';
        $currentUrl = $scheme . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        $parts = parse_url($currentUrl);

        // Remove oauth callback params
        $query = '';
        if (isset($parts['query'])) {
            parse_str($parts['query'], $params);
            foreach (array('code', 'scope', 'error', 'error_description') as $name) {
                unset($params[$name]);
            }
            if (count($params) > 0) {
                $query = '?' . http_build_query($params, null, '&');
            }
        }
        // Use port if non default
        $port = isset($parts['port']) && ($secure ? $parts['port'] !== 80 : $parts['port'] !== 443) ? ':' . $parts['port'] : '';
        // rebuild
        return $scheme . $parts['host'] . $port . $parts['path'] . $query;
    }

    /**
     * Check if an url is valid
     * @param array $url
     * @return boolean
     */
    private function isValidUrl($url)
    {
        return filter_var($url, FILTER_VALIDATE_URL);
    }
}

