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

use Guzzle\Plugin\Log\LogPlugin;
use Guzzle\Log\PsrLogAdapter;
use Guzzle\Http\Client as Guzzle;
use Guzzle\Plugin\Cache\CachePlugin;
use PhraseanetSDK\Cache\CacheFactory;
use PhraseanetSDK\Cache\RevalidationFactory;
use PhraseanetSDK\Cache\CanCacheStrategy;
use PhraseanetSDK\Authentication\StoreInterface;
use PhraseanetSDK\Authentication\DefaultStore;
use PhraseanetSDK\Exception\AuthenticationException;
use PhraseanetSDK\Exception\BadRequestException;
use PhraseanetSDK\Exception\BadResponseException;
use PhraseanetSDK\Exception\RuntimeException;
use PhraseanetSDK\Exception\TransportException;
use PhraseanetSDK\Exception\InvalidArgumentException;
use PhraseanetSDK\HttpAdapter\Response;
use PhraseanetSDK\HttpAdapter\HttpAdapterInterface;
use PhraseanetSDK\HttpAdapter\Guzzle as GuzzleAdapter;
use Symfony\Component\HttpFoundation\Request;

/**
 * Phraseanet SDK Client, perform the HTTP requests against Phraseanet API
 */
class Client implements ClientInterface
{
    const VERSION = '0.3-dev';

    /**
     * The OAuth authorization server endpoint URL
     */
    private $oauthAuthorizeEndpointUrl = '';

    /**
     * The OAuth token server endpoint URL
     */
    private $oauthTokenEndpointUrl = '';

    /**
     * @var HttpAdapterInterface
     */
    private $httpClient;

    /**
     * Api ClientId
     *
     * @var string
     */
    private $clientId;

    /**
     * Api Secret
     *
     * @var string
     */
    private $secret;

    /**
     * Grant type
     *
     * @var string
     */
    private $grantType;

    /**
     * Infos associated to the grant type
     *
     * @var array
     */
    private $grantInfo;

    /**
     *
     * @var StoreInterface
     */
    private $tokenStore;

    /**
     * To create an API client-id/secret pair, go to your account adminstation panel
     * in your phraseanet application.
     *
     * @param string               $clientId   Your API ClientId
     * @param string               $secret     Your API secret
     * @param HttpAdapterInterface $clientHttp An HTTP Client
     */
    public function __construct($clientId, $secret, HttpAdapterInterface $clientHttp)
    {
        $this->setHttpClient($clientHttp);

        $baseUrl = rtrim($this->httpClient->getBaseUrl(), '/');
        $this->httpClient->setBaseUrl($baseUrl . '/api/v1');

        $this->oauthAuthorizeEndpointUrl = sprintf('%s%s', $baseUrl, self::AUTH_ENDPOINT);
        $this->oauthTokenEndpointUrl = sprintf('%s%s', $baseUrl, self::TOKEN_ENDPOINT);

        $this->clientId = $clientId;
        $this->secret = $secret;
        $this->tokenStore = new DefaultStore();
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityManager()
    {
        return new EntityManager($this);
    }

    /**
     * Set the token store
     *
     * @param  StoreInterface $store
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
     * @param  string $token
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
     * @param HttpAdapter\HttpAdapterInterface $client
     *
     * @return Client
     */
    public function setHttpClient(HttpAdapter\HttpAdapterInterface $client)
    {
        $this->httpClient = $client;
        $client->setUserAgent(sprintf('Phraseanet API SDK Version %s', static::VERSION));

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
            , 'client_id'     => $this->clientId
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
                'client_id'     => $this->clientId,
                'client_secret' => $this->secret,
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
     * {@inheritdoc}
     */
    public function call($method, $path, $query = array(), $postFields = array())
    {
        $responseContent = null;

        $args['oauth_token'] = $this->getAccessToken();

        switch (strtoupper($method)) {
            case 'POST' :
                $start = microtime(true);
                $responseContent = $this->httpClient->post($path, $query, $postFields);
                $stop = microtime(true);
                break;
            case 'GET' :
                $start = microtime(true);
                $responseContent = $this->httpClient->get($path, $query);
                $stop = microtime(true);
                break;
            default :
                throw new BadRequestException(sprintf('Phraseanet API do not support %s method', $method));
        }

        if (null === $json = json_decode($responseContent)) {
            throw new RuntimeException('Json response cannot be decoded or the encoded data is deeper than the recursion limit');
        }

        return new Response($json);
    }

    /**
     * Creates a Client.
     *
     * @param array $config
     *
     * @return Client
     *
     * @throws InvalidArgumentException In case a parameter is missing
     */
    public static function create(array $config)
    {
        $config = array_replace_recursive(array(
            'client-id' => null,
            'secret'    => null,
            'url'       => null,
            'token'     => null,
            'logger'    => null,
            'cache' => array(
                'type' => 'array',
                'host' => null,
                'port' => null,
                'ttl'  => 300,
                'revalidate' => 'skip',
            ),
            'guzzle' => array(
                'plugins' => array(),
            ),
        ), $config);

        foreach (array('client-id', 'secret', 'url') as $key) {
            if (null === $config[$key]) {
                throw new InvalidArgumentException(sprintf('Missing parameter %s', $key));
            }
        }

        if (!isset($config['cache']['factory'])) {
            $config['cache']['factory'] = new CacheFactory();
        }
        if (!isset($config['guzzle']['revalidation-factory'])) {
            $config['guzzle']['revalidation-factory'] = new RevalidationFactory();
        }
        if (!isset($config['guzzle']['can-cache-strategy'])) {
            $config['guzzle']['can-cache-strategy'] = new CanCacheStrategy();
        }

        $guzzle = new Guzzle($config['url']);

        if (null !== $config['logger']) {
            $logger = $config['logger'];
            $guzzle->addSubscriber(new LogPlugin(new PsrLogAdapter($logger)));
        }

        try {
            $cacheAdapter = $config['cache']['factory']->createGuzzleCacheAdapter($config['cache']['type'], $config['cache']['host'], $config['cache']['port']);
            if (isset($logger)) {
                $logger->debug(sprintf('Using cache adapter %s', $config['cache']['type']));
            }
        } catch (RuntimeException $e) {
            if (isset($logger)) {
                $logger->error(sprintf('Unable to create cache adapter %s', $config['cache']['type']));
            }
            $cacheAdapter = $config['cache']['factory']->createGuzzleCacheAdapter('array');
        }

        $guzzle->addSubscriber(new CachePlugin(array(
            'adapter'      => $cacheAdapter,
            'can_cache'    => $config['guzzle']['can-cache-strategy'],
            'default_ttl'  => $config['cache']['ttl'],
            'revalidation' => $config['guzzle']['revalidation-factory']->create($config['cache']['revalidate']),
        )));

        foreach ($config['guzzle']['plugins'] as $plugin) {
            $guzzle->addSubscriber($plugin);
        }

        $client = new Client($config['client-id'], $config['secret'], new GuzzleAdapter($guzzle));
        $client->setAccessToken($config['token']);

        return $client;
    }

    /**
     * Returns the current URL, removing of known OAuth parameters that should not persist.
     *
     * @return String the current URL
     */
    private function getUrlWithoutOauth2Parameters(Request $request)
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
