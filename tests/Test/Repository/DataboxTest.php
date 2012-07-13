<?php

namespace Test\Repository;

require_once 'Repository.php';

use PhraseanetSDK\Client;
use PhraseanetSDK\Repository\Databox;
use PhraseanetSDK\EntityManager;

class DataboxTest extends Repository
{
    public function testFindAll()
    {
        $client = $this->getClient($this->getSampleResponse('repository/databox/findAll'));
        $databoxRepository = new Databox(new EntityManager($client));
        $databoxes = $databoxRepository->findAll();
        $this->assertIsCollection($databoxes);
        foreach ($databoxes as $databox) {
            $this->checkDatabox($databox);
        }
    }

    /**
     * @expectedException PhraseanetSDK\Exception\UnauthorizedException
     */
    public function testFindAllException()
    {
        $client = $this->getClient($this->getSampleResponse('401'), 401);
        $feedRepository = new Databox(new EntityManager($client));
        $feedRepository->findAll();
    }

    /**
     * @expectedException PhraseanetSDK\Exception\RuntimeException
     */
    public function testFindAllRuntimeException()
    {
        $client = $this->getClient($this->getSampleResponse('empty'));
        $feedRepository = new Databox(new EntityManager($client));
        $feedRepository->findAll();
    }
}
