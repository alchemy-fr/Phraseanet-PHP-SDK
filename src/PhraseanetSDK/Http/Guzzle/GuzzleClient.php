<?php

/*
 * This file is part of Phraseanet SDK.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhraseanetSDK\Http\Guzzle;

use Guzzle\Common\Exception\GuzzleException;
use Guzzle\Http\Client as Guzzle;
use Guzzle\Http\ClientInterface;
use Guzzle\Http\Exception\BadResponseException as GuzzleBadResponse;
use Guzzle\Http\Exception\CurlException;
use Guzzle\Http\Message\EntityEnclosingRequestInterface;
use Guzzle\Http\Message\RequestInterface;
use PhraseanetSDK\ApplicationInterface;
use PhraseanetSDK\Exception\BadResponseException;
use PhraseanetSDK\Exception\InvalidArgumentException;
use PhraseanetSDK\Exception\RuntimeException;
use PhraseanetSDK\Http\Client;
use PhraseanetSDK\Http\Endpoint;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class GuzzleClient implements Client
{
    /**
     * Creates a new instance of GuzzleAdapter
     *
     * @param Endpoint $endpoint
     * @param EventSubscriberInterface[] $plugins
     * @return static
     */
    public static function create(Endpoint $endpoint, array $plugins = array())
    {
        $userAgent = sprintf('%s version %s', ApplicationInterface::USER_AGENT, ApplicationInterface::VERSION);
        $guzzle = new Guzzle($endpoint->getUrl());

        $guzzle->setUserAgent($userAgent);

        foreach ($plugins as $plugin) {
            $guzzle->addSubscriber($plugin);
        }

        return new static($guzzle, $endpoint);
    }

    /**
     * @var ClientInterface
     */
    private $guzzle;

    /**
     * @var Endpoint
     */
    private $endpoint;

    /**
     * @param ClientInterface $guzzle
     * @param Endpoint $endpoint
     */
    public function __construct(ClientInterface $guzzle, Endpoint $endpoint)
    {
        $this->guzzle = $guzzle;
        $this->endpoint = $endpoint;
    }

    /**
     * @return Endpoint
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * @return ClientInterface
     */
    public function getGuzzle()
    {
        return $this->guzzle;
    }

    /**
     * Returns the client base URL
     *
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->guzzle->getBaseUrl();
    }

    /**
     * Sets the user agent
     *
     * @param string $userAgent
     */
    public function setUserAgent($userAgent)
    {
        $this->guzzle->setUserAgent($userAgent);
    }

    /**
     * Performs an HTTP request, returns the body response
     *
     * @param string $method The method
     * @param string $path The path to query
     * @param array $query An array of query parameters
     * @param array $postFields An array of post fields
     * @param array $files An array of post files
     * @param array $headers An array of request headers
     *
     * @return string The response body
     *
     * @throws BadResponseException
     * @throws RuntimeException
     */
    public function call(
        $method,
        $path,
        array $query = array(),
        array $postFields = array(),
        array $files = array(),
        array $headers = array()
    ) {
        try {
            $request = $this->guzzle->createRequest($method, $path, $headers);
            $this->addRequestParameters($request, $query, $postFields, $files);
            $response = $request->send();
        } catch (CurlException $e) {
            throw new RuntimeException($e->getMessage(), $e->getErrorNo(), $e);
        } catch (GuzzleBadResponse $e) {
            throw BadResponseException::fromGuzzleResponse($e);
        } catch (GuzzleException $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }

        return $response->getBody(true);
    }

    private function addRequestParameters(RequestInterface $request, $query, $postFields, $files)
    {
        foreach ($query as $name => $value) {
            $request->getQuery()->add($name, $value);
        }

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
}
