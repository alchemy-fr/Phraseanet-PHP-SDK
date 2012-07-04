<?php

namespace Test\Repository;

require_once 'Repository.php';

use PhraseanetSDK\Client;
use PhraseanetSDK\Repository\Entry;
use PhraseanetSDK\Tools\Entity\Manager;

class EntryTest extends Repository
{

    public function testFindById()
    {
        $client = $this->getClient($this->getSampleResponse('repository/entry/byId'));
        $entryRepo = new Entry(new Manager($client));
        $entry = $entryRepo->findById(1);
        $this->checkFeedEntry($entry);
    }

    /**
     * @expectedException PhraseanetSDK\Exception\UnauthorizedException
     */
    public function testFindByIdException()
    {
        $client = $this->getClient($this->getSampleResponse('401'), 401);
        $feedRepository = new Entry(new Manager($client));
        $feedRepository->findById(44);
    }

    /**
     * @expectedException PhraseanetSDK\Exception\RuntimeException
     */
    public function testFindByIdRuntimeException()
    {
        $client = $this->getClient($this->getSampleResponse('empty'));

        $feedRepository = new Entry(new Manager($client));
        $feedRepository->findById(44);
    }

    public function testFindByFeed()
    {
        $client = $this->getClient($this->getSampleResponse('repository/entry/byFeed'));
        $feedRepository = new Entry(new Manager($client));
        $entries = $feedRepository->findByFeed(1);
        $this->assertIsCollection($entries);
        foreach ($entries as $entry) {
            $this->checkFeedEntry($entry);
        }
    }

    /**
     * @expectedException PhraseanetSDK\Exception\UnauthorizedException
     */
    public function testFindAllException()
    {
        $client = $this->getClient($this->getSampleResponse('401'), 401);

        $feedRepository = new Entry(new Manager($client));
        $feedRepository->findByFeed(44);
    }

    /**
     * @expectedException PhraseanetSDK\Exception\RuntimeException
     */
    public function testFindAllRuntimeException()
    {
        $client = $this->getClient($this->getSampleResponse('empty'));

        $feedRepository = new Entry(new Manager($client));
        $feedRepository->findByFeed(44);
    }

    public function testFindInAggregated()
    {
        $client = $this->getClient($this->getSampleResponse('repository/entry/aggregated'));

        $feedRepository = new Entry(new Manager($client));
        $entries = $feedRepository->findInAggregatedFeed();
        $this->assertIsCollection($entries);
        foreach ($entries as $entry) {
            $this->checkFeedEntry($entry);
        }
    }

    /**
     * @expectedException PhraseanetSDK\Exception\RuntimeException
     */
    public function testFindInAggregatedRuntimeException()
    {
        $client = $this->getClient($this->getSampleResponse('empty'));

        $feedRepository = new Entry(new Manager($client));
        $feedRepository->findInAggregatedFeed();
    }
}
