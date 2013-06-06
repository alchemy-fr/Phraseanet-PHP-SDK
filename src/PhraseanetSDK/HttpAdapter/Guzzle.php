<?php

/*
 * This file is part of Phraseanet SDK.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhraseanetSDK\HttpAdapter;

use Guzzle\Common\Exception\GuzzleException;
use Guzzle\Http\ClientInterface;
use Guzzle\Http\Exception\BadResponseException as GuzzleBadResponse;
use Guzzle\Http\Exception\CurlException;
use PhraseanetSDK\Exception\BadResponseException;
use PhraseanetSDK\Exception\RuntimeException;
use Guzzle\Http\Message\EntityEnclosingRequestInterface;
use Guzzle\Http\Message\RequestInterface;
use PhraseanetSDK\Exception\InvalidArgumentException;

class Guzzle implements HttpAdapterInterface
{
    /** @var ClientInterface */
    private $client;
    /** @var string */
    private $token;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * {@inheritdoc}
     *
     * @return ClientInterface
     */
    public function getAdapter()
    {
        return $this->client;
    }

    /**
     * Get client base URL
     *
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->client->getBaseUrl();
    }

    /**
     * Set client base URL
     *
     * @param  string                            $url
     * @return \PhraseanetSDK\HttpAdapter\Guzzle
     */
    public function setBaseUrl($url)
    {
        $this->client->setBaseUrl($url);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function get($path, array $query = array())
    {
        return $this->doMethod('GET', $path, $query);
    }

    /**
     * {@inheritdoc}
     */
    public function post($path, array $query = array(), array $postFields = array())
    {
        return $this->doMethod('POST', $path, $query, $postFields);
    }

    /**
     * {@inheritdoc}
     */
    public function setUserAgent($ua)
    {
        $this->client->setUserAgent($ua);
    }

    private function doMethod($name, $path, array $query, array $postFields = array())
    {
        $query = array_replace($query, array('token' => $this->token));

        try {
            $request = $this->client->createRequest($name, $path, array('Accept', 'application/json'));
            $this->addRequestParameters($request, $query, $postFields);
            $response = $request->send();
        } catch (CurlException $e) {
            throw new RuntimeException($e->getMessage(), $e->getErrorNo(), $e);
        } catch (GuzzleBadResponse $e) {
            throw BadResponseException::fromGuzzleResponse($e);
        } catch (GuzzleException $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }

        return $response->getBody();
    }

    private function addRequestParameters(RequestInterface $request, $query, $postFields)
    {
        foreach ($query as $name => $value) {
            $request->getQuery()->add($name, $value);
        }

        if ($request instanceof EntityEnclosingRequestInterface) {
            foreach ($postFields as $name => $value) {
                $request->getPostFields()->add($name, $value);
            }
        } elseif (0 < count($postFields)) {
            throw new InvalidArgumentException('Can not add post fields to GET request');
        }
    }
}
