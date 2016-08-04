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

use PhraseanetSDK\Exception\InvalidArgumentException;

/**
 * Response object from a Phraseanet API call
 */
class ApiResponse
{
    /**
     * @var \stdClass
     */
    protected $result;

    /**
     * @var \stdClass
     */
    private $meta;

    /**
     * @param \stdClass $response
     */
    public function __construct(\stdClass $response)
    {
        if (!isset($response->meta) || !isset($response->response)) {
            throw new InvalidArgumentException('Malformed API response');
        }

        $this->meta = $response->meta;
        $this->result = $response->response;
    }

    /**
     * Returns the result of the response
     *
     * @return \stdClass
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Returns the HTTP code
     *
     * @return integer
     */
    public function getStatusCode()
    {
        return (int)$this->meta->http_code;
    }

    /**
     * Returns true is the response is successful
     *
     * @return Boolean
     */
    public function isOk()
    {
        return $this->getStatusCode() < 400;
    }

    /**
     * Returns true if the response content is empty
     *
     * @return Boolean
     */
    public function isEmpty()
    {
        return count(get_object_vars($this->result)) === 0;
    }

    /**
     * Returns the error message
     *
     * @return string|null
     */
    public function getErrorMessage()
    {
        return $this->meta->error_message;
    }

    /**
     * Returns error details
     *
     * @return string|null
     */
    public function getErrorDetails()
    {
        return $this->meta->error_details;
    }

    /**
     * Returns the response datetime
     *
     * @return \DateTime
     */
    public function getResponseTime()
    {
        return \DateTime::createFromFormat(DATE_ATOM, $this->meta->response_time);
    }

    /**
     * Returns the requested URI
     *
     * @return string
     */
    public function getUri()
    {
        $request = explode(' ', $this->meta->request);

        return $request[1];
    }

    /**
     * Returns the requested method
     *
     * @return string
     */
    public function getMethod()
    {
        $request = explode(' ', $this->meta->request);

        return $request[0];
    }

    /**
     * Returns the response charset
     *
     * @return string
     */
    public function getCharset()
    {
        return $this->meta->charset;
    }

    /**
     * Returns the API version
     *
     * @return string
     */
    public function getApiVersion()
    {
        return $this->meta->api_version;
    }

    /**
     * Returns true if the response has the given property
     *
     * @param string $property The property name
     *
     * @return Boolean
     */
    public function hasProperty($property)
    {
        return property_exists($this->result, $property);
    }

    /**
     * Returns the response property, null if the property does not exist
     *
     * @param string $property The property name
     *
     * @return \stdClass|\stdClass[]|null
     */
    public function getProperty($property)
    {
        return $this->hasProperty($property) ? $this->result->{$property} : null;
    }
}
