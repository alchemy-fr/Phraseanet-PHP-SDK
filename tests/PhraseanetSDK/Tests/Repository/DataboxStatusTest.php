<?php

namespace PhraseanetSDK\Tests\Repository;

use PhraseanetSDK\Repository\DataboxStatus;
use PhraseanetSDK\EntityManager;

class DataboxStatusTest extends RepositoryTestCase
{

    public function testFindByDatabox()
    {
        $client = $this->getClient($this->getSampleResponse('repository/databoxStatus/findAll'));
        $databoxStatusRepository = new DataboxStatus(new EntityManager($client));
        $databoxStatus = $databoxStatusRepository->findByDatabox(1);

        foreach ($databoxStatus as $status) {
            $this->checkDataBoxStatus($status);
        }
    }

    public function testFindByDataboxException()
    {
        $this->expectException('PhraseanetSDK\Exception\UnauthorizedException');

        $client = $this->getClient($this->getSampleResponse('401'), 401);
        $databoxStatusRepository = new DataboxStatus(new EntityManager($client));
        $databoxStatusRepository->findByDatabox(1);
    }

    public function testFindByDataboxRuntimeException()
    {
        $this->expectException('PhraseanetSDK\Exception\RuntimeException');

        $client = $this->getClient($this->getSampleResponse('empty'));
        $databoxStatusRepository = new DataboxStatus(new EntityManager($client));
        $databoxStatusRepository->findByDatabox(1);
    }
}
