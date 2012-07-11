<?php

namespace PhraseanetSDK\HttpAdapter;

use Guzzle\Common\GuzzleException;
use Guzzle\Http\ClientInterface;
use Guzzle\Http\Exception\BadResponseException as GuzzleBadResponse;
use Guzzle\Http\Exception\CurlException;
use Monolog\Logger;
use PhraseanetSDK\Exception\BadResponseException;
use PhraseanetSDK\Exception\RuntimeException;

class Guzzle implements HttpAdapterInterface
{
    /**
     * The guzzle client
     *
     * @var Guzzle\Http\ClientInterface
     */
    private $client;

    /**
     * The oauth token
     *
     * @var string
     */
    private $token;


    /**
     * A monolog logger
     *
     * @var \Monolog\Logger
     */
    private $logger;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
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
     * A logger
     *
     * @param \Monolog\Logger $logger
     */
    public function setLogger(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * GET request
     *
     * @param  string               $path The path to query
     * @param  array                $args An array of query parameters
     * @return string               The response body
     * @throws BadResponseException
     * @throws RuntimeException
     */
    public function get($path, array $args = array())
    {
        $queryDatas = $this->formatQueryParameters($args);

        $path = sprintf('%s%s', ltrim($path, '/'), $this->getTemplate($queryDatas['data']));

        try {
            $request = $this->client->get(array($path, $queryDatas));
            $request->setHeader('Accept', 'application/json');
            $this->log($request->getRawHeaders());
            $response = $request->send();
            $this->log($response->getRawHeaders());
        } catch (CurlException $e) {
            throw new RuntimeException($e->getMessage(), $e->getErrorNo(), $e);
        } catch (GuzzleBadResponse $e) {
            $response = $e->getResponse();
            $ex = new BadResponseException($response->getReasonPhrase(), $e->getCode(), $e);
            $ex->setResponseBody($response->getBody())->setHttpStatusCode($response->getStatusCode());
            throw $ex;
        } catch (GuzzleException $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }

        return $response->getBody();
    }

    /**
     * Post request
     *
     * @param  string               $path The path to query
     * @param  array                $args An array of query parameters
     * @return string               The response body
     * @throws BadResponseException
     * @throws RuntimeException
     */
    public function post($path, array $args = array())
    {
        $queryDatas = $this->formatQueryParameters($args);

        $path = sprintf('%s%s', ltrim($path, '/'), $this->getTemplate($queryDatas['data']));

        try {
            $request = $this->client->post(array($path, $queryDatas));
            $request->setHeader('Accept', 'application/json');
            $this->log($request->getRawHeaders());
            $response = $request->send();
            $this->log($response->getRawHeaders());
        } catch (CurlException $e) {
            throw new RuntimeException($e->getMessage(), $e->getErrorNo(), $e);
        } catch (GuzzleBadResponse $e) {
            $response = $e->getResponse();
            $ex = new BadResponseException($response->getReasonPhrase(), $e->getCode(), $e);
            $ex->setResponseBody($response->getBody())->setHttpStatusCode($response->getStatusCode());

            throw $ex;
        } catch (GuzzleException $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }

        return $response->getBody();
    }

    /**
     * Return an URI template
     *
     * @param  array  $args
     * @return string
     */
    private function getTemplate(array $args)
    {
        return '{?' . (null !== $this->token ? 'oauth_token,' : '') . ( ! empty($args) ? 'data*' : '' ) . '}';
    }

    /**
     * Format query parameters
     *
     * @param  array $args
     * @return array
     */
    private function formatQueryParameters($args)
    {
        if (isset($args['oauth_token'])) {
            $this->token = $args['oauth_token'];
            unset($args['oauth_token']);
        }

        $queryDatas = array('data' => $args);

        if ($this->token) {
            $queryDatas['oauth_token'] = $this->token;
        }

        return $queryDatas;
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
