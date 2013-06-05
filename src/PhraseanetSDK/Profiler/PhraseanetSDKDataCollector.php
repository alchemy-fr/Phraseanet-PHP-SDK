<?php

namespace PhraseanetSDK\Profiler;

use Guzzle\Plugin\History\HistoryPlugin;
use Guzzle\Http\Message\RequestInterface;
use Guzzle\Http\Message\Response as GuzzleResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Guzzle\Http\Message\EntityEnclosingRequestInterface;

/**
 * GuzzleDataCollector.
 *
 * @author Ludovic Fleury <ludo.flery@gmail.com>
 */
class PhraseanetSDKDataCollector extends DataCollector
{
    private $profiler;

    public function __construct(HistoryPlugin $profiler)
    {
        $this->profiler = $profiler;
    }

    /**
     * {@inheritdoc}
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data = array(
            'calls'       => array(),
            'error_count' => 0,
            'methods'     => array(),
            'total_time'  => 0,
        );

        foreach ($this->profiler as $call) {
            $error = false;
            $request = $call;
            $response = $request->getResponse();

            $requestContent = null;
            if ($request instanceof EntityEnclosingRequestInterface) {
                $requestContent = (string) $request->getBody();
            }
            $responseContent = $this->prettifyResponse($response->getBody(true));

            $time = array(
                'total' => $response->getInfo('total_time'),
                'connection' => $response->getInfo('connect_time')
            );

            $this->data['total_time'] += $response->getInfo('total_time');

            if (!isset($this->data['methods'][$request->getMethod()])) {
                $this->data['methods'][$request->getMethod()] = 0;
            }

            $this->data['methods'][$request->getMethod()]++;

            if ($response->isError()) {
                $this->data['error_count']++;
                $error = true;
            }

            $this->data['calls'][] = array(
                'request' => $this->sanitizeRequest($request),
                'requestContent' => $requestContent,
                'response' => $this->sanitizeResponse($response),
                'responseContent' => $responseContent,
                'time' => $time,
                'error' => $error
            );
        }
    }

    /**
     * @return array
     */
    public function getCalls()
    {
        return isset($this->data['calls']) ? $this->data['calls'] : array();
    }

    /**
     * @return int
     */
    public function countErrors()
    {
        return isset($this->data['error_count']) ? $this->data['error_count'] : 0;
    }

    /**
     * @return array
     */
    public function getMethods()
    {
        return isset($this->data['methods']) ? $this->data['methods'] : array();
    }

    /**
     * @return int
     */
    public function getTotalTime()
    {
        return isset($this->data['total_time']) ? $this->data['total_time'] : 0;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'phrasea-sdk';
    }

    /**
     * @param RequestInterface $request
     *
     * @return array
     */
    private function sanitizeRequest(RequestInterface $request)
    {
        return array(
            'method'           => $request->getMethod(),
            'protocol_version' => $request->getProtocolVersion(),
            'path'             => $request->getPath(),
            'scheme'           => $request->getScheme(),
            'host'             => $request->getHost(),
            'query'            => $request->getQuery(),
            'headers'          => $request->getHeaders()->toArray(),
            'query_parameters' => $request->getUrl(true)->getQuery(),
        );
    }

    /**
     * @param GuzzleResponse $response
     *
     * @return array
     */
    private function sanitizeResponse($response)
    {
        return array(
            'statusCode'   => $response->getStatusCode(),
            'reasonPhrase' => $response->getReasonPhrase(),
            'headers'      => $response->getHeaders()->toArray(),
        );
    }

    private function prettifyResponse($body)
    {
        if (!defined('JSON_PRETTY_PRINT')) {
            return $body;
        }

        $data = @json_decode($body);

        if (JSON_ERROR_NONE !== json_last_error()) {
            return $body;
        }

        return json_encode($data, JSON_PRETTY_PRINT);
    }
}
