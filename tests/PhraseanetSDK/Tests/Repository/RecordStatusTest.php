<?php

namespace PhraseanetSDK\Tests\Repository;

use PhraseanetSDK\Repository\RecordStatus;
use PhraseanetSDK\EntityManager;

class RecordStatusTest extends RepositoryTestCase
{

    public function testFindByRecord()
    {
        $client = $this->getClient($this->getSampleResponse('repository/recordStatus/byRecord'));
        $statusRepository = new RecordStatus(new EntityManager($client));
        $status = $statusRepository->findByRecord(1, 1);
        $this->assertIsCollection($status);
        foreach ($status as $oneStatus) {
            $this->checkRecordStatus($oneStatus);
        }
    }

    public function testFindByRecordException()
    {
        $this->expectException('PhraseanetSDK\Exception\RuntimeException');

        $client = $this->getClient($this->getSampleResponse('empty'));

        $statusRepository = new RecordStatus(new EntityManager($client));
        $record = $this->createMock('\\PhraseanetSDK\\Entity\Record');
        $statusRepository->findByRecord(1, 1);
    }
}
