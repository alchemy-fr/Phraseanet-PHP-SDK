<?php

namespace Test\Repository;

require_once 'Repository.php';

use PhraseanetSDK\Client;
use PhraseanetSDK\Repository\DataboxCollection;
use PhraseanetSDK\Tools\Entity\Manager;

class DataboxCollectionTest extends Repository
{
    public function testFindByDatabox()
    {
        $client = $this->getClient($this->getSampleResponse('repository/databoxCollection/findAll'));
        $collectionRepository = new DataboxCollection(new Manager($client));
        $collections = $collectionRepository->findByDatabox(1);

        foreach ($collections as $collection) {
            $this->checkDataboxCollection($collection);
        }
    }

    /**
     * @expectedException PhraseanetSDK\Exception\UnauthorizedException
     */
    public function testFindByDataboxException()
    {
        $client = $this->getClient($this->getSampleResponse('401'), 401);
        $collectionRepository = new DataboxCollection(new Manager($client));
        $collectionRepository->findByDatabox(1);
    }

    /**
     * @expectedException PhraseanetSDK\Exception\RuntimeException
     */
    public function testFindByDataboxRuntimeException()
    {
        $client = $this->getClient($this->getSampleResponse('empty'));
        $collectionRepository = new DataboxCollection(new Manager($client));
        $collectionRepository->findByDatabox(1);
    }

}
