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
use PhraseanetSDK\Cache\CacheFactory;

class GuzzleAdapter implements GuzzleAdapterInterface
{
    /** @var ClientInterface */
    private $guzzle;
    private $extended = false;

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
     * @param string $userAgent
     */
    public function setUserAgent($userAgent)
    {
        $this->guzzle->setUserAgent($userAgent);
    }

    /**
     * Sets extended mode
     *
     * Extended mode fetch more data (status, meta, subdefs) in one request
     * for a record
     *
     * @param boolean $extended
     */
    public function setExtended($extended)
    {
        $this->extended = (boolean) $extended;
    }

    /**
     * @return boolean
     */
    public function isExtended()
    {
        return $this->extended;
    }

    /**
     * Performs an HTTP request, returns the body response
     *
     * @param string $method     The method
     * @param string $path       The path to query
     * @param array  $query      An array of query parameters
     * @param array  $postFields An array of post fields
     * @param array  $files      An array of post files
     * @param array  $headers    An array of request headers
     *
     * @return string The response body
     *
     * @throws BadResponseException
     * @throws RuntimeException
     */
    public function call($method, $path, array $query = array(), array $postFields = array(), array $files = array(), $headers = array())
    {
        echo  __METHOD__."\n";
        try {
            $request = $this->guzzle->createRequest($method, $path, array_merge(array('accept' =>
                $this->extended ? 'application/vnd.phraseanet.record-extended+json' : 'application/json'
            ), $headers));
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

    /**
     * Creates a new instance of GuzzleAdapter
     *
     * @param array $config
     * @param array $plugins
     *
     * @return static
     */
    public static function create($endpoint, array $plugins = array())
    {
        if (!is_string($endpoint)) {
            throw new InvalidArgumentException('API url endpoint must be a valid url');
        }
        // test if url already end with API_MOUNT_POINT
        if (ApplicationInterface::API_MOUNT_POINT !== substr(trim($endpoint, '/'), -strlen(ApplicationInterface::API_MOUNT_POINT))) {
            $endpoint = sprintf('%s%s/', trim($endpoint, '/'), ApplicationInterface::API_MOUNT_POINT);
        }

        $guzzle = new Guzzle($endpoint);
        $guzzle->setUserAgent(sprintf('%s version %s', ApplicationInterface::USER_AGENT, ApplicationInterface::VERSION));

        foreach ($plugins as $plugin) {
            $guzzle->addSubscriber($plugin);
        }

        return new static($guzzle);
    }

    private function addRequestParameters(RequestInterface $request, $query, $postFields, $files)
    {
        foreach ($query as $name => $value) {
            $request->getQuery()->add($name, $value);
        }

        if ($request instanceof EntityEnclosingRequestInterface) {
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
