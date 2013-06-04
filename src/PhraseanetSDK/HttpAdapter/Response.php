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

use PhraseanetSDK\Exception\InvalidArgumentException;

/**
 * Response object from a Phraseanet API call
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
     * @param \stdClass $response
     */
    public function __construct(\stdClass $response)
    {
        if ( ! isset($response->meta) || ! isset($response->response)) {
            throw new InvalidArgumentException('The API json response is malformed');
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
     * @return integer
     */
    public function getHttpStatusCode()
    {
        return (int) $this->meta->http_code;
    }

    /**
     * Check id the Response is a success
     * @return integer
     */
    public function isOk()
    {
        return $this->getHttpStatusCode() < 400;
    }

    /**
     * Checker whether the response content is empty
     * @return Boolean
     */
    public function isEmpty()
    {
        return count(get_object_vars($this->result)) === 0;
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
     * @return \DateTime
     */
    public function getResponseTime()
    {
        return \DateTime::createFromFormat(DATE_ATOM, $this->meta->response_time);
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

    /**
     * Check existence of $property in response object
     *
     * @param  string  $property property name
     * @return Boolean
     */
    public function hasProperty($property)
    {
        return property_exists($this->result, $property);
    }

    /**
     * Check existence of $property in response object
     *
     * @param  string  $property property name
     * @return Boolean
     */
    public function getProperty($property)
    {
        return $this->hasProperty($property) ? $this->result->{$property} : null;
    }
}
