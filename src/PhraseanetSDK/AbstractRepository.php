<?php

/*
 * This file is part of Phraseanet SDK.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhraseanetSDK;

use PhraseanetSDK\EntityManager;
use PhraseanetSDK\Exception\BadResponseException;
use PhraseanetSDK\Exception\NotFoundException;
use PhraseanetSDK\Exception\TokenExpiredException;
use PhraseanetSDK\Exception\UnauthorizedException;
use PhraseanetSDK\Exception\RuntimeException;
use PhraseanetSDK\Http\ApiClient;
use PhraseanetSDK\Http\ApiResponse;
use PhraseanetSDK\Http\APIGuzzleAdapter;
use PhraseanetSDK\Http\Client;

abstract class AbstractRepository
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->client = $this->entityManager->getClient();
    }

    /**
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @return ApiClient
     */
    protected function getClient()
    {
        return $this->entityManager->getClient();
    }

    /**
     * Query the API
     *
     * @param string $method HTTP method type (POST, GET ...)
     * @param string $path The requested path (/path/to/ressource/1)
     * @param array $query An array of query parameters
     * @param array $postFields An array of request parameters
     * @param array $headers
     *
     * @return ApiResponse
     * @throws NotFoundException
     * @throws UnauthorizedException
     */
    protected function query($method, $path, $query = array(), $postFields = array(), array $headers = array())
    {
        try {
            $response = $this->getClient()->call($method, $path, $query, $postFields, array(), $headers);
        } catch (BadResponseException $e) {
            $statusCode = $e->getStatusCode();
            switch ($statusCode) {
                case 404:
                    throw new NotFoundException(sprintf('Resource under %s could not be found', $path));
                    break;
                case 401:
                    throw new UnauthorizedException(sprintf('Access to the following resource %s is forbidden', $path));
                    break;
                case 400:
                    throw new TokenExpiredException('Token is expired or email validation is already done');
                    break;
                default:
                    throw new RuntimeException(sprintf('Something went wrong "%s (%s)"', $e->getMessage(), $e->get));
            }
        }

        return $response;
    }
}
