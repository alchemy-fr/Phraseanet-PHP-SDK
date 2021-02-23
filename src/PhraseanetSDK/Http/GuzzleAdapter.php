<?php

/*
 * This file is part of Phraseanet SDK.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhraseanetSDK\Http;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\BadResponseException as GuzzleBadResponse;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
// use GuzzleLogMiddleware\LogMiddleware;
use PhraseanetSDK\ApplicationInterface;
use PhraseanetSDK\Exception\BadResponseException;
use PhraseanetSDK\Exception\InvalidArgumentException;
use PhraseanetSDK\Exception\RuntimeException;
use Psr\Http\Message\RequestInterface;
// use Symfony\Component\EventDispatcher\EventSubscriberInterface;


class GuzzleAdapter implements GuzzleAdapterInterface
{
    /** @var ClientInterface */
    private $guzzleClient;
    private $extended = false;

    /** @var string
     * since client->getConfig() is deprecated, we keep here a copy of the endpoint passed on "create()"
     */
    private $baseUri = '';

    /**
     * @var string
     *  since client->setUserAgent() is removed, we keep it here and pass it on exery "call()"
     */
    private $userAgent = '';

    public function __construct(ClientInterface $guzzleClient)
    {
        $this->guzzleClient = $guzzleClient;
    }

    /**
     * {@inheritdoc}
     *
     * @return ClientInterface
     */
    public function getGuzzle(): ClientInterface
    {
        return $this->guzzleClient;
    }

    /**
     * Sets the baseUrl
     *
     * @param string $baseUrl
     * @return GuzzleAdapter
     */
    public function setBaseUrl(string $baseUrl): GuzzleAdapterInterface
    {
        $this->baseUri = $baseUrl;

        return $this;
    }

    /**
     * Returns the client base URL
     *
     * @return string
     */
    public function getBaseUrl(): string
    {
        // return $this->guzzleClient->getBaseUrl();  // removed
        return $this->baseUri;
    }

    /**
     * Sets the user agent
     *
     * @param string $userAgent
     * @return GuzzleAdapter
     */
    public function setUserAgent(string $userAgent): GuzzleAdapterInterface
    {
        // $this->guzzleClient->setUserAgent($userAgent);  // removed
        $this->userAgent = $userAgent;

        return $this;
    }

    /**
     * @return string
     */
    public function getUserAgent(): string
    {
        return ($this->userAgent);
    }

    /**
     * Sets extended mode
     *
     * Extended mode fetch more data (status, meta, subdefs) in one request
     * for a record
     *
     * @param bool $extended
     * @return GuzzleAdapter
     */
    public function setExtended(bool $extended): GuzzleAdapterInterface
    {
        $this->extended = $extended;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isExtended(): bool
    {
        return $this->extended;
    }

    /**
     * Performs an HTTP request, returns the body response
     *
     * @param string $method    The method
     * @param string $path      The path to query
     * @param array $query      An array of query parameters
     * @param array $postFields An array of post fields
     * @param array $files      An array of post files
     * @param array $headers    An array of request headers
     *
     * @return string The response body
     *
     * @throws InvalidArgumentException
     * @throws BadResponseException
     * @throws RuntimeException
     */
    public function call(string $method, string $path, array $query = [], array $postFields = [], array $files = [], array $headers = []): string
    {
        try {
            $acceptHeader = [
                'Accept' => $this->extended ? 'application/vnd.phraseanet.record-extended+json' : 'application/json'
            ];

            $options = [
                RequestOptions::QUERY => $query
            ];

            // files -- receiving files has no usage found in the code, so format of $files is unknown, so... not implmented
            if (count($files) > 0) {
                throw new InvalidArgumentException('request with "files" is not implemented');
            }

            // postFields
            if (count($postFields) > 0) {
                if ($method !== 'POST') {
                    throw new InvalidArgumentException('postFields are only allowed with "POST" method');
                }
                if (count($files) > 0) {
                    // this will not happen while "files" is not implemented
                    throw new InvalidArgumentException('request can\'t contain both postFields and files');
                }
                $options[RequestOptions::FORM_PARAMS] = $postFields;
            }

            // headers
            $h = array_merge($acceptHeader, $headers);
            if ($this->userAgent !== '' && !array_key_exists('User-Agent', $h)) {
                // use the defaut user-agent if none is provided in headers
                $h['User-Agent'] = sprintf('%s version %s', ApplicationInterface::USER_AGENT, ApplicationInterface::VERSION);
            }
            if (count($h) > 0) {
                $options[RequestOptions::HEADERS] = $h;
            }

            $response = $this->guzzleClient->request($method, $path, $options);

//            $request = new Request($method, $path, array_merge($acceptHeader, $headers));

//            $this->addRequestParameters($request, $query, $postFields, $files);
//            $response = $request->send();
        }
        catch (GuzzleBadResponse $e) {
            throw BadResponseException::fromGuzzleResponse($e);
        }
        catch (GuzzleException $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }

        return $response->getBody();
    }

    /**
     * Creates a new instance of GuzzleAdapter
     *
     * @param string $endpoint
     * @return static
     */
    public static function create(string $endpoint): GuzzleAdapterInterface
    {
        if (!is_string($endpoint)) {
            throw new InvalidArgumentException('API url endpoint must be a valid url');
        }

        $versionMountPoint = ApplicationInterface::API_MOUNT_POINT;

        // test if url already end with API_MOUNT_POINT
        $mountPoint = substr(trim($endpoint, '/'), -strlen($versionMountPoint));

        if ($versionMountPoint !== $mountPoint) {
            $endpoint = sprintf('%s%s/', trim($endpoint, '/'), $versionMountPoint);
        }

        $guzzleClient = new GuzzleClient([
            'base_uri'              => $endpoint,
            RequestOptions::HEADERS => [
                'User-Agent' => sprintf('%s version %s', ApplicationInterface::USER_AGENT, ApplicationInterface::VERSION)
            ]
        ]);

//        $guzzleClient->setUserAgent(sprintf(
//            '%s version %s',
//            ApplicationInterface::USER_AGENT,
//            ApplicationInterface::VERSION
//        ));

        /* todo : for now, no plugins
        $logger = new Logger();  //A new PSR-3 Logger like Monolog
        $stack = HandlerStack::create(); // will create a stack stack with middlewares of guzzle already pushed inside of it.
        $stack->push(new LogMiddleware($logger));
        foreach ($plugins as $plugin) {
            $guzzleClient->addSubscriber($plugin);
        }
        */

        return (new static($guzzleClient))->setBaseUrl($endpoint);
    }

    /*
    private function addRequestParameters(RequestInterface $request, $query, $postFields, $files)
    {
        foreach ($query as $name => $value) {
            $request->getQuery()->add($name, $value);
        }

        // todo : EntityEnclosingRequestInterface ???
        if ($request instanceof EntityEnclosingRequestInterface) {
            if ($request->getHeader('Content-Type') == 'application/json') {
                $request->getHeaders()->offsetUnset('Content-Type');
                $request->setBody(json_encode($postFields));

                return;
            }

            foreach ($postFields as $name => $value) {
                $request->getPostFields()->add($name, $value);
            }
            foreach ($files as $name => $filename) {
                $request->addPostFile($name, $filename);
            }
        } elseif (0 < count($postFields)) {
            throw new InvalidArgumentException('Can not add post fields to GET request');
        }
    }
    */
}
