<?php

namespace PhraseanetSDK\HttpAdapter;

use Guzzle\Common\Exception\GuzzleException;
use Guzzle\Http\ClientInterface;
use Guzzle\Http\Exception\BadResponseException as GuzzleBadResponse;
use Guzzle\Http\Exception\CurlException;
use PhraseanetSDK\Exception\BadResponseException;
use PhraseanetSDK\Exception\RuntimeException;

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
    public function get($path, array $args = array())
    {
        return $this->doMethod('get', $path, $args);
    }

    /**
     * {@inheritdoc}
     */
    public function post($path, array $args = array())
    {
        return $this->doMethod('post', $path, $args);
    }

    private function doMethod($name, $path, $args)
    {
        $queryDatas = $this->formatQueryParameters($args);
        $path = sprintf('%s%s', ltrim($path, '/'), $this->getTemplate($queryDatas['data']));

        try {
            $request = call_user_func(array($this->client, $name), array($path, $queryDatas));
            $request->setHeader('Accept', 'application/json');
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
}
