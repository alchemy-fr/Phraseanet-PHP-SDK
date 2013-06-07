<?php

/*
 * This file is part of Phraseanet SDK.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhraseanetSDK\Repository;

use PhraseanetSDK\EntityManager;
use PhraseanetSDK\Exception\BadResponseException;
use PhraseanetSDK\Exception\NotFoundException;
use PhraseanetSDK\Exception\UnauthorizedException;
use PhraseanetSDK\Exception\RuntimeException;
use PhraseanetSDK\Http\APIResponse;
use PhraseanetSDK\Http\APIGuzzleAdapter;

abstract class AbstractRepository implements RepositoryInterface
{
    /** @var EntityManager */
    protected $em;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @return APIGuzzleAdapter
     */
    private function getAdapter()
    {
        return $this->em->getAdapter();
    }

    /**
     * Query the API
     *
     * @param  string                $method HTTP method type (POST, GET ...)
     * @param  string                $path   The requested path (/path/to/ressource/1)
     * @param  array                 $params An array of query parameters
     * @return APIResponse
     * @throws NotFoundException
     * @throws UnauthorizedException
     * @throws RuntimeException
     */
    protected function query($method, $path, $query = array(), $postFields = array())
    {
        try {
            $response = $this->getAdapter()->call($method, $path, $query, $postFields);
        } catch (BadResponseException $e) {
            $statusCode = $e->getStatusCode();
            switch ($statusCode) {
                case 404:
                    throw new NotFoundException(sprintf('Resource under %s could not be found', $path));
                    break;
                case 401:
                    throw new UnauthorizedException(sprintf('Access to the following resource %s is forbidden', $path));
                    break;
                default:
                    throw new RuntimeException(sprintf('Something went wrong "%s"', $e->getMessage()));
            }
        }

         return $response;
    }
}
