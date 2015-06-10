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
    /**
     * @var HistoryPlugin
     */
    private $profiler;

    /**
     * @var bool
     */
    private $truncateResponse;

    public function __construct(HistoryPlugin $profiler, $truncateResponse = true)
    {
        $this->profiler = $profiler;
        $this->truncateResponse = (bool)$truncateResponse;
    }

    /**
     * {@inheritdoc}
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data = array(
            'calls'       => array(),
            'cache_hits'  => 0,
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
                'connection' => $response->getInfo('connect_time'),
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

            if (substr($response->getHeaders()->get('X-Cache', ''), 0, 3) == 'HIT') {
                $this->data['cache_hits'] += 1;
            }

            $this->data['calls'][] = array(
                'request' => $this->sanitizeRequest($request),
                'requestContent' => $requestContent,
                'response' => $this->sanitizeResponse($response),
                'responseContent' => json_decode($responseContent),
                'time' => $time,
                'error' => $error,
                'phraseanet' => $this->parsePhraseanetResponse($response)
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
     * @return int|void
     */
    public function getCacheHitRatio()
    {
        $totalCalls = count($this->getCalls());

        if (! isset($this->data['cache_hits']) || $totalCalls == 0) {
            return 0;
        }

        return $this->data['cache_hits'] * 100 / $totalCalls;
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
        $postParameters = $request instanceof EntityEnclosingRequestInterface ? $request->getPostFields() : null;

        return array(
            'method'           => $request->getMethod(),
            'protocol_version' => $request->getProtocolVersion(),
            'path'             => $request->getPath(),
            'scheme'           => $request->getScheme(),
            'host'             => $request->getHost(),
            'query'            => $request->getQuery(),
            'headers'          => $request->getHeaders()->toArray(),
            'query_parameters' => $request->getUrl(true)->getQuery(),
            'post_parameters'  => $postParameters,
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
            return $this->limitLength($body);
        }

        $data = @json_decode($body);

        if (JSON_ERROR_NONE !== json_last_error()) {
            return $this->limitLength($body);
        }

        return $this->limitLength(json_encode($data, JSON_PRETTY_PRINT));
    }

    private function limitLength($string, $length = 600)
    {
        if ($this->truncateResponse === false) {
            return $string;
        }

        if (strlen($string)) {
            return substr($string, 0, $length)."\n\n truncated response\n";
        } else {
            return $string;
        }
    }

    private function parsePhraseanetResponse($response)
    {
        if ($response->getStatusCode() !== 200) {
            return array();
        }

        $body = $response->getBody(true);
        $data = json_decode($body, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return array();
        }

        $parsed = array(
            'metadata' => $data['meta']
        );

        if (isset($data['response']['offset_start'])) {
            $pagination = array(
                'Offset' => $data['response']['offset_start'],
                'Page size' => $data['response']['per_page'],
                'Page max size' => isset($data['response']['available_results']) ? $data['response']['available_results'] : '-',
                'Total results' => isset($data['response']['total_results']) ? $data['response']['total_results'] : '-'
            );

            $parsed['pagination'] = $pagination;
        }

        return $parsed;
    }
}
