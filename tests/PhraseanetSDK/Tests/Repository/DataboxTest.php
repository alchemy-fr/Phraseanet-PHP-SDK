<?php

namespace PhraseanetSDK\Tests\Repository;

use PhraseanetSDK\Repository\Databox;
use PhraseanetSDK\EntityManager;

class DataboxTest extends RepositoryTestCase
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

    public function testFindAllException()
    {
        $this->expectException('PhraseanetSDK\Exception\UnauthorizedException');

        $client = $this->getClient($this->getSampleResponse('401'), 401);
        $feedRepository = new Databox(new EntityManager($client));
        $feedRepository->findAll();
    }

    public function testFindAllRuntimeException()
    {
        $this->expectException('PhraseanetSDK\Exception\RuntimeException');

        $client = $this->getClient($this->getSampleResponse('empty'));
        $feedRepository = new Databox(new EntityManager($client));
        $feedRepository->findAll();
    }
}
