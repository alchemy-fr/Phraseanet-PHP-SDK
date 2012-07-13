<?php

namespace Test\Repository;

require_once 'Repository.php';

use PhraseanetSDK\Client;
use PhraseanetSDK\Repository\RecordStatus;
use PhraseanetSDK\EntityManager;

class RecordStatusTest extends Repository
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

    /**
     * @expectedException PhraseanetSDK\Exception\RuntimeException
     */
    public function testFindByRecordException()
    {
        $client = $this->getClient($this->getSampleResponse('empty'));

        $statusRepository = new RecordStatus(new EntityManager($client));
        $record = $this->getMock('\\PhraseanetSDK\\Entity\Record', array(), array(), '', false);
        $statusRepository->findByRecord(1, 1);
    }
}
