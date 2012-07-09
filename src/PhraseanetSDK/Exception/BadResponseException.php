<?php

namespace PhraseanetSDK\Exception;

class BadResponseException extends \Exception implements ExceptionInterface
{
    /**
     * The content of the bad response
     *
     * @param string $body
     */
    protected $responseBody;

    /**
     * The response status code
     *
     * @var int
     */
    protected $httpStatusCode;

    /**
     *
     * @param string $body
     * @return \PhraseanetSDK\Exception\BadResponseException
     */
    public function setResponseBody($body)
    {
        $this->responseBody = (string) $body;

        return $this;
    }

    /**
     * The content of the response
     *
     * @return string
     */
    public function getResponseBody()
    {
        return $this->responseBody;
    }

    /**
     * The response HTTP status code
     *
     * @return int
     */
    public function getHttpStatusCode()
    {
        return $this->httpStatusCode;
    }

    /**
     * Set the response HTTP status code
     *
     * @param int $httpStatusCode
     * @return \PhraseanetSDK\Exception\BadResponseException
     */
    public function setHttpStatusCode($httpStatusCode)
    {
        $this->httpStatusCode = $httpStatusCode;

        return $this;
    }

        /**
     * Checks if HTTP Status code is a Client Error (4xx)
     *
     * @return bool
     */
    public function isClientError()
    {
        return substr(strval($this->httpStatusCode), 0, 1) == '4';
    }

    /**
     * Checks if HTTP Status code is Server Error (5xx)
     *
     * @return bool
     */
    public function isServerError()
    {
        return substr(strval($this->httpStatusCode), 0, 1) == '5';
    }
}
