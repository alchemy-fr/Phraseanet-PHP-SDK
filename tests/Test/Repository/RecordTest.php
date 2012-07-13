<?php

namespace Test\Repository;

require_once 'Repository.php';

use PhraseanetSDK\Client;
use PhraseanetSDK\Repository\Record;
use PhraseanetSDK\EntityManager;

class RecordTest extends Repository
{

    public function testFindById()
    {
        $client = $this->getClient($this->getSampleResponse('repository/record/idByDatabox'));
        $recordRepo = new Record(new EntityManager($client));
        $record = $recordRepo->findById(1, 1);
        $this->checkRecord($record);
    }

    /**
     * @expectedException PhraseanetSDK\Exception\RuntimeException
     */
    public function testFindIdByDataboxException()
    {
        $client = $this->getClient($this->getSampleResponse('empty'));
        $recordRepo = new Record(new EntityManager($client));
        $recordRepo->findById(1, 1);
    }

    public function testFind()
    {
        $client = $this->getClient($this->getSampleResponse('repository/query/search'));
        $recordRepo = new Record(new EntityManager($client));
        $records = $recordRepo->find(1, 10);
        $this->assertIsCollection($records);
        foreach ($records as $record) {
            $this->checkRecord($record);
        }
    }

    /**
     * @expectedException PhraseanetSDK\Exception\RuntimeException
     */
    public function testFindException()
    {
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

    /**
     * @expectedException PhraseanetSDK\Exception\RuntimeException
     */
    public function testSearchException()
    {
        $client = $this->getClient($this->getSampleResponse('empty'));
        $recordRepo = new Record(new EntityManager($client));
        $recordRepo->search();
    }
}
