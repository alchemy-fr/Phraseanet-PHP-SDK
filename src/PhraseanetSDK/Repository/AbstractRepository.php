<?php

namespace PhraseanetSDK\Repository;

use PhraseanetSDK\EntityManager;
use PhraseanetSDK\Exception\BadResponseException;
use PhraseanetSDK\Exception\NotFoundException;
use PhraseanetSDK\Exception\UnauthorizedException;
use PhraseanetSDK\Exception\RuntimeException;

abstract class AbstractRepository implements RepositoryInterface
{
    /**
     *
     * @var Manager
     */
    protected $em;

    /**
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @codeCoverageIgnore
     * @return PhraseanetSDK\Client
     */
    private function getClient()
    {
        return $this->em->getClient();
    }

    /**
     * Query the API
     *
     * @param  string                 $method HTTP method type (POST, GET ...)
     * @param  string                 $path   The requested path (/path/to/ressource/1)
     * @param  array                  $params An array of query parameters
     * @return PhraseanetSDK\Response
     * @throws NotFoundException
     * @throws UnauthorizedException
     * @throws RuntimeException
     */
    protected function query($method, $path, $params = array())
    {
        try {
            $response = $this->getClient()->call($path, $params, $method);
        } catch (BadResponseException $e) {
            $httpStatusCode = $e->getHttpStatusCode();
            switch ($httpStatusCode) {
                case 404:
                    throw new NotFoundException(sprintf('Ressource under %s could not be found', $path));
                    break;
                case 401:
                    throw new UnauthorizedException(sprintf('Access to the following ressource %s is forbidden', $path));
                    break;
                default:
                    throw new RuntimeException(sprintf('Something went wrong "%s"', $e->getMessage()));
            }
        }

         return $response;
    }
}
