<?php

namespace Test\Repository;

require_once 'Repository.php';

use PhraseanetSDK\Client;
use PhraseanetSDK\Repository\Feed;
use PhraseanetSDK\Tools\Entity\Manager;

class FeedTest extends Repository
{

    public function testFindById()
    {
        $client = $this->getClient($this->getSampleResponse('repository/feed/findById'));
        $feedRepository = new Feed(new Manager($client));
        $feed = $feedRepository->findById(1);
        $this->checkFeed($feed);
    }

    /**
     * @expectedException PhraseanetSDK\Exception\UnauthorizedException
     */
    public function testFindByIdException()
    {
        $client = $this->getClient($this->getSampleResponse('401'), 401);
        $feedRepository = new Feed(new Manager($client));
        $feedRepository->findById(44);
    }

    /**
     * @expectedException PhraseanetSDK\Exception\RuntimeException
     */
    public function testFindByIdRuntimeException()
    {
        $client = $this->getClient($this->getSampleResponse('empty'));
        $feedRepository = new Feed(new Manager($client));
        $feedRepository->findById(44);
    }

    public function testFindAll()
    {
        $client = $this->getClient($this->getSampleResponse('repository/feed/findAll'));
        $feedRepository = new Feed(new Manager($client));
        $feeds = $feedRepository->findAll();
        $this->assertIsCollection($feeds);
        foreach ($feeds as $feed) {
            $this->checkFeed($feed);
        }
    }

    /**
     * @expectedException PhraseanetSDK\Exception\UnauthorizedException
     */
    public function testFindAllException()
    {
        $client = $this->getClient($this->getSampleResponse('401'), 401);
        $feedRepository = new Feed(new Manager($client));
        $feedRepository->findAll();
    }

    /**
     * @expectedException PhraseanetSDK\Exception\RuntimeException
     */
    public function testFindAllRuntimeException()
    {
        $client = $this->getClient($this->getSampleResponse('empty'));

        $feedRepository = new Feed(new Manager($client));
        $feedRepository->findAll();
    }

}
