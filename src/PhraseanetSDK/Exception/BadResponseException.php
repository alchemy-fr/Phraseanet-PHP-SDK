<?php

/*
 * This file is part of Phraseanet SDK.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhraseanetSDK\Exception;

use Guzzle\Http\Exception\BadResponseException as GuzzleBadResponseException;

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
     * @param  string                                        $body
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
     * @return integer
     */
    public function getStatusCode()
    {
        return $this->httpStatusCode;
    }

    /**
     * Set the response HTTP status code
     *
     * @param  integer                                       $httpStatusCode
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

    public static function fromGuzzleResponse(GuzzleBadResponseException $e)
    {
        $response = $e->getResponse();

        $exception = new static($response->getReasonPhrase(), $e->getCode(), $e);
        $exception
            ->setResponseBody($response->getBody())
            ->setHttpStatusCode($response->getStatusCode());

        return $exception;
    }
}
