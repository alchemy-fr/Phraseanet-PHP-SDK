<?php

namespace PhraseanetSDK\Tests\Repository;

use PhraseanetSDK\Repository\Entry;
use PhraseanetSDK\EntityManager;

class EntryTest extends RepositoryTestCase
{

    public function testFindById()
    {
        $client = $this->getClient($this->getSampleResponse('repository/entry/byId'));
        $entryRepo = new Entry(new EntityManager($client));
        $entry = $entryRepo->findById(1);
        $this->checkFeedEntry($entry);
    }

    public function testFindByIdException()
    {
        $this->expectException('PhraseanetSDK\Exception\UnauthorizedException');

        $client = $this->getClient($this->getSampleResponse('401'), 401);
        $feedRepository = new Entry(new EntityManager($client));
        $feedRepository->findById(44);
    }

    public function testFindByIdRuntimeException()
    {
        $this->expectException('PhraseanetSDK\Exception\RuntimeException');

        $client = $this->getClient($this->getSampleResponse('empty'));

        $feedRepository = new Entry(new EntityManager($client));
        $feedRepository->findById(44);
    }

    public function testFindByFeed()
    {
        $client = $this->getClient($this->getSampleResponse('repository/entry/byFeed'));
        $feedRepository = new Entry(new EntityManager($client));
        $entries = $feedRepository->findByFeed(1);
        $this->assertIsCollection($entries);
        foreach ($entries as $entry) {
            $this->checkFeedEntry($entry);
        }
    }

    public function testFindAllException()
    {
        $this->expectException('PhraseanetSDK\Exception\UnauthorizedException');

        $client = $this->getClient($this->getSampleResponse('401'), 401);

        $feedRepository = new Entry(new EntityManager($client));
        $feedRepository->findByFeed(44);
    }

    public function testFindAllRuntimeException()
    {
        $this->expectException('PhraseanetSDK\Exception\RuntimeException');

        $client = $this->getClient($this->getSampleResponse('empty'));

        $feedRepository = new Entry(new EntityManager($client));
        $feedRepository->findByFeed(44);
    }

    public function testFindInAggregated()
    {
        $client = $this->getClient($this->getSampleResponse('repository/entry/aggregated'));

        $feedRepository = new Entry(new EntityManager($client));
        $entries = $feedRepository->findInAggregatedFeed();
        $this->assertIsCollection($entries);
        foreach ($entries as $entry) {
            $this->checkFeedEntry($entry);
        }
    }

    public function testFindInAggregatedRuntimeException()
    {
        $this->expectException('PhraseanetSDK\Exception\RuntimeException');

        $client = $this->getClient($this->getSampleResponse('empty'));

        $feedRepository = new Entry(new EntityManager($client));
        $feedRepository->findInAggregatedFeed();
    }
}
