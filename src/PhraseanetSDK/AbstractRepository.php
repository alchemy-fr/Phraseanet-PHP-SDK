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
use PhraseanetSDK\Http\APIResponse;
use PhraseanetSDK\Http\APIGuzzleAdapter;

abstract class AbstractRepository
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var APIGuzzleAdapter
     */
    private $adapter;

    /**
     * @param EntityManager $em
     * @param APIGuzzleAdapter $adapter
     */
    public function __construct(EntityManager $em, APIGuzzleAdapter $adapter = null)
    {
        $this->em = $em;
        $this->adapter = $adapter ?: $this->em->getAdapter();
    }

    /**
     * @return APIGuzzleAdapter
     */
    private function getAdapter(): APIGuzzleAdapter
    {
        return $this->adapter;
    }

    /**
     * Query the API
     *
     * @param string $method    HTTP method type (POST, GET ...)
     * @param string $path      The requested path (/path/to/ressource/1)
     * @param array $query      An array of query parameters
     * @param array $postFields An array of request parameters
     * @param array $headers
     *
     * @return APIResponse
     * @throws NotFoundException
     * @throws UnauthorizedException|TokenExpiredException
     */
    protected function query(string $method, string $path, $query = array(), $postFields = array(), array $headers = array()): APIResponse
    {
        try {
            $response = $this->getAdapter()->call($method, $path, $query, $postFields, array(), $headers);
        }
        catch (BadResponseException $e) {
            $statusCode = $e->getStatusCode();
            switch ($statusCode) {
                case 404:
                    throw new NotFoundException(sprintf('Resource under %s could not be found', $path));
                case 401:
                    throw new UnauthorizedException(sprintf('Access to the following resource %s is forbidden', $path));
                case 400:
                    throw new TokenExpiredException('Token is expired or email validation is already done');
                default:
                    throw new RuntimeException(sprintf('Something went wrong "%s"', $e->getMessage()));
            }
        }

        return $response;
    }
}
