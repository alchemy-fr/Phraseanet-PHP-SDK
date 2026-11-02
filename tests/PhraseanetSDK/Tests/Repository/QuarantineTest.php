<?php

namespace PhraseanetSDK\Tests\Repository;

use PhraseanetSDK\Repository\Quarantine;
use PhraseanetSDK\EntityManager;

class QuarantineTest extends RepositoryTestCase
{

    public function testFindByOffset()
    {
        $client = $this->getClient($this->getSampleResponse('repository/quarantine/findAll'));
        $quarantineRepository = new Quarantine(new EntityManager($client));
        $items = $quarantineRepository->findByOffset(0, 10);

        $this->assertIsCollection($items);

        foreach ($items as $item) {
            $this->checkQuarantine($item);
        }
    }

    public function testFindById()
    {
        $client = $this->getClient($this->getSampleResponse('repository/quarantine/findById'));
        $quarantineRepository = new Quarantine(new EntityManager($client));
        $item = $quarantineRepository->findById(1);

        $this->checkQuarantine($item);
    }

    public function testFindByIdRuntimeException()
    {
        $this->expectException('PhraseanetSDK\Exception\RuntimeException');

        $client = $this->getClient($this->getSampleResponse('empty'));
        $quarantineRepository = new Quarantine(new EntityManager($client));
        $quarantineRepository->findById(1);
    }

    public function testFindByOffsetByNameException()
    {
        $this->expectException('PhraseanetSDK\Exception\NotFoundException');

        $client = $this->getClient($this->getSampleResponse('repository/quarantine/findAll'), 404);
        $quarantineRepository = new Quarantine(new EntityManager($client));
        $quarantineRepository->findByOffset(0, 10);
    }

    public function testFindByOffsetdException()
    {
        $this->expectException('PhraseanetSDK\Exception\UnauthorizedException');

        $client = $this->getClient($this->getSampleResponse('401'), 401);
        $quarantineRepository = new Quarantine(new EntityManager($client));
        $quarantineRepository->findByOffset(0, 10);
    }

    public function testFindByOffsetdQueryRuntimeException()
    {
        $this->expectException('PhraseanetSDK\Exception\RuntimeException');

        $client = $this->getClient($this->getSampleResponse('bad'), 200);
        $quarantineRepository = new Quarantine(new EntityManager($client));
        $quarantineRepository->findByOffset(0, 10);
    }

    public function testFindByOffsetdRuntimeException()
    {
        $this->expectException('PhraseanetSDK\Exception\RuntimeException');

        $client = $this->getClient($this->getSampleResponse('empty'), 200);
        $quarantineRepository = new Quarantine(new EntityManager($client));
        $quarantineRepository->findByOffset(0, 10);
    }
}
