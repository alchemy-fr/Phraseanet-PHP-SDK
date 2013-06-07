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

use Guzzle\Common\Exception\GuzzleException;
use Guzzle\Http\ClientInterface;
use Guzzle\Http\Exception\BadResponseException as GuzzleBadResponse;
use Guzzle\Http\Exception\CurlException;
use PhraseanetSDK\Exception\BadResponseException;
use PhraseanetSDK\Exception\RuntimeException;
use Guzzle\Http\Message\EntityEnclosingRequestInterface;
use Guzzle\Http\Message\RequestInterface;
use PhraseanetSDK\Exception\InvalidArgumentException;

class GuzzleAdapter implements GuzzleAdapterInterface
{
    /** @var ClientInterface */
    private $guzzle;

    public function __construct(ClientInterface $guzzle)
    {
        $this->guzzle = $guzzle;
    }

    /**
     * {@inheritdoc}
     *
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
     * @param type $ua
     */
    public function setUserAgent($userAgent)
    {
        $this->guzzle->setUserAgent($userAgent);
    }

    /**
     * Performs an HTTP request, returns the body response
     *
     * @param string $method       The method
     * @param string $path       The path to query
     * @param array  $query      An array of query parameters
     * @param array  $postFields An array of post fields
     *
     * @return string The response body
     *
     * @throws BadResponseException
     * @throws RuntimeException
     */
    public function call($method, $path, array $query = array(), array $postFields = array())
    {
        try {
            $request = $this->guzzle->createRequest($method, $path, array('accept' => 'application/json'));
            $this->addRequestParameters($request, $query, $postFields);
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
