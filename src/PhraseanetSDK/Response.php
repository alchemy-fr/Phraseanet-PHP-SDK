<?php

namespace PhraseanetSDK;

use PhraseanetSDK\Exception;

/**
 * 
 * Handle Response from a Phraseanet API call
 */
class Response
{

    /**
     *
     * @var stdClass
     */
    protected $result;

    /**
     *
     * @var stdClass
     */
    private $meta;

    /**
     *
     * @param stdClass $response 
     */
    public function __construct(\stdClass $response = null)
    {
        if (null === $response)
        {
            throw new Exception\ApiResponseException('Response is empty');
        }

        if ( ! isset($response->meta) || ! isset($response->response))
        {
            throw new Exception\ApiResponseException('Response is malformed');
        }

        $this->meta = $response->meta;
        $this->result = $response->response;
    }

    /**
     * Return the result of the response
     * @return stdClass 
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Return the HTTP code
     * @return int 
     */
    public function getHttpStatusCode()
    {
        return (int) $this->meta->http_code;
    }

    /**
     * Check id the Response is a success
     * @return int 
     */
    public function isOk()
    {
        return floor($this->getHttpStatusCode() / 100) < 4;
    }

    /**
     * Get error message if present
     * @return mixed 
     * Return the Error message if present
     * Return null
     */
    public function getErrorMessage()
    {
        return $this->meta->error_message;
    }

    /**
     * Get error detail if present
     * @return mixed 
     * Return the Error details if present
     * Return null
     */
    public function getErrorDetails()
    {
        return $this->meta->error_details;
    }

    /**
     * Get Response time 
     * @return DateTime 
     */
    public function getResponseTime()
    {
        return new DateTime($this->meta->response_time);
    }

    /**
     * Get requested URI
     * @return string
     */
    public function getUri()
    {
        $request = explode(' ', $this->meta->request);
        return $request[1];
    }

    /**
     * Get Requested method
     * @return string 
     */
    public function getMethod()
    {
        $request = explode(' ', $this->meta->request);
        return $request[0];
    }

    /**
     * Get Response charset
     * @return string 
     */
    public function getCharset()
    {
        return $this->meta->charset;
    }

    /**
     * get API version
     * @return string 
     */
    public function getApiVersion()
    {
        return $this->meta->api_version;
    }

}
