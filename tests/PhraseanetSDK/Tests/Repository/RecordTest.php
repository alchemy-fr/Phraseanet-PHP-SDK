<?php

namespace PhraseanetSDK\Tests\Repository;

use PhraseanetSDK\Repository\Record;
use PhraseanetSDK\EntityManager;

class RecordTest extends RepositoryTestCase
{

    public function testFindById()
    {
        $client = $this->getClient($this->getSampleResponse('repository/record/idByDatabox'));
        $recordRepo = new Record(new EntityManager($client));
        $record = $recordRepo->findById(1, 1);
        $this->checkRecord($record);
    }

    public function testFindIdByDataboxException()
    {
        $this->expectException('PhraseanetSDK\Exception\RuntimeException');

        $client = $this->getClient($this->getSampleResponse('empty'));
        $recordRepo = new Record(new EntityManager($client));
        $recordRepo->findById(1, 1);
    }

    public function testFind()
    {
        $client = $this->getClient($this->getSampleResponse('repository/record/find'));
        $recordRepo = new Record(new EntityManager($client));
        $records = $recordRepo->find(1, 10);
        $this->assertIsCollection($records);
        foreach ($records as $record) {
            $this->checkRecord($record);
        }
    }

    public function testFindException()
    {
        $this->expectException('PhraseanetSDK\Exception\RuntimeException');

        $client = $this->getClient($this->getSampleResponse('empty'));
        $recordRepo = new Record(new EntityManager($client));
        $recordRepo->find(1, 10);
    }

    public function testSearch()
    {
        $client = $this->getClient($this->getSampleResponse('repository/query/search'));
        $recordRepo = new Record(new EntityManager($client));
        $query = $recordRepo->search();
        $this->checkQueryObject($query);
    }

    public function testSearchException()
    {
        $this->expectException('PhraseanetSDK\Exception\RuntimeException');

        $client = $this->getClient($this->getSampleResponse('empty'));
        $recordRepo = new Record(new EntityManager($client));
        $recordRepo->search();
    }
}
