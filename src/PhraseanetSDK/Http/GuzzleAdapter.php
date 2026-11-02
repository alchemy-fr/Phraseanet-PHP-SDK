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

use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\BadResponseException as GuzzleBadResponse;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\RequestOptions;
use PhraseanetSDK\ApplicationInterface;
use PhraseanetSDK\Exception\BadResponseException;
use PhraseanetSDK\Exception\InvalidArgumentException;
use PhraseanetSDK\Exception\RuntimeException;

class GuzzleAdapter implements GuzzleAdapterInterface
{
    /** @var ClientInterface */
    private $guzzle;
    private $extended = false;
    private $sslVerification = false;
    private $userAgent;

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
        return (string) $this->guzzle->getConfig('base_uri');
    }

    /**
     * Sets the user agent
     *
     * @param string $userAgent
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;
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
        $this->extended = (boolean)$extended;
    }

    /**
     * @return boolean
     */
    public function isExtended()
    {
        return $this->extended;
    }

    /**
     * Sets setSslVerification mode
     *
     * @param boolean $sslVerification
     */
    public function setSslVerification($sslVerification)
    {
        $this->sslVerification = (boolean)$sslVerification;
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
            $acceptHeader = array(
                'Accept' => $this->extended ? 'application/vnd.phraseanet.record-extended+json' : 'application/json'
            );

            if ($this->userAgent !== null) {
                $acceptHeader['User-Agent'] = $this->userAgent;
            }

            $headers = array_merge($acceptHeader, $headers);
            $options = array_merge(
                array(RequestOptions::HEADERS => $headers, RequestOptions::VERIFY => $this->sslVerification),
                $this->buildRequestOptions($method, $query, $postFields, $files, $headers)
            );
            $response = $this->guzzle->request($method, $path, $options);
        } catch (ConnectException $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        } catch (GuzzleBadResponse $e) {
            throw BadResponseException::fromGuzzleResponse($e);
        } catch (GuzzleException $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }

        return (string) $response->getBody();
    }

    /**
     * Creates a new instance of GuzzleAdapter
     *
     * @param string $endpoint
     * @param callable[] $middlewares Guzzle middlewares
     * @return static
     */
    public static function create(
        $endpoint,
        array $middlewares = array()
    ) {
        if (!is_string($endpoint)) {
            throw new InvalidArgumentException('API url endpoint must be a valid url');
        }

        $versionMountPoint = ApplicationInterface::API_MOUNT_POINT;

        // test if url already end with API_MOUNT_POINT
        $mountPoint = substr(trim($endpoint, '/'), -strlen($versionMountPoint));

        if ($versionMountPoint !== $mountPoint) {
            $endpoint = sprintf('%s%s/', trim($endpoint, '/'), $versionMountPoint);
        }

        $stack = HandlerStack::create();

        foreach ($middlewares as $middleware) {
            $stack->push($middleware);
        }

        $adapter = new static(new Guzzle(array('base_uri' => $endpoint, 'handler' => $stack)));
        $adapter->setUserAgent(sprintf(
            '%s version %s',
            ApplicationInterface::USER_AGENT,
            ApplicationInterface::VERSION
        ));

        return $adapter;
    }

    private function buildRequestOptions($method, array $query, array $postFields, array $files, array $headers)
    {
        $options = array();

        if (0 < count($query)) {
            $options[RequestOptions::QUERY] = http_build_query($query);
        }

        if (!in_array(strtoupper($method), array('POST', 'PUT', 'PATCH', 'DELETE'), true)) {
            if (0 < count($postFields)) {
                throw new InvalidArgumentException('Can not add post fields to GET request');
            }

            return $options;
        }

        if (($headers['Content-Type'] ?? null) === 'application/json') {
            $options[RequestOptions::BODY] = json_encode($postFields);

            return $options;
        }

        if (0 < count($files)) {
            $options[RequestOptions::MULTIPART] = $this->buildMultipart($postFields, $files);
        } elseif (0 < count($postFields)) {
            $options[RequestOptions::FORM_PARAMS] = $postFields;
        }

        return $options;
    }

    private function buildMultipart(array $postFields, array $files)
    {
        $parts = array();

        foreach (explode('&', http_build_query($postFields)) as $pair) {
            if ($pair !== '') {
                list($name, $value) = array_map('urldecode', explode('=', $pair, 2));
                $parts[] = array('name' => $name, 'contents' => $value);
            }
        }

        foreach ($files as $name => $filename) {
            $parts[] = array('name' => $name, 'contents' => fopen($filename, 'r'), 'filename' => basename($filename));
        }

        return $parts;
    }
}
