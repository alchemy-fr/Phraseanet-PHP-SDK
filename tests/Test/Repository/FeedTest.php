<?php

namespace Test\Repository;

require_once 'Repository.php';

use Guzzle;
use Doctrine\Common\Collections\ArrayCollection;
use PhraseanetSDK\Client;
use PhraseanetSDK\Response;
use PhraseanetSDK\Repository\Feed;
use PhraseanetSDK\Tools\Entity\Manager;

class FeedTest extends Repository
{

    /**
     *
     * @dataProvider feedProvider
     */
    public function testFindById($idFeed)
    {
        $client = $this->getClient($this->getSampleResponse('repository/feed/findById'));

        $feedRepository = new Feed(new Manager($client));
        $feed = $feedRepository->findById($idFeed);

        $this->assertTrue($feed instanceof \PhraseanetSDK\Entity\Feed);
        $this->assertEquals(1, $feed->getEntries()->count());
    }

    /**
     * @expectedException PhraseanetSDK\Exception\ApiResponseException
     */
    public function testFindByIdException()
    {
        $client = $this->getClient($this->getSampleResponse('401'));

        $feedRepository = new Feed(new Manager($client));
        $feedRepository->findById(44);
    }

    public function testFindAll()
    {
        $client = $this->getClient($this->getSampleResponse('repository/feed/findAll'));

        $feedRepository = new Feed(new Manager($client));
        $feeds = $feedRepository->findAll();

        $this->assertTrue($feeds instanceof ArrayCollection);
        $this->assertEquals(3, $feeds->count());
    }

    /**
     * @expectedException PhraseanetSDK\Exception\ApiResponseException
     */
    public function testFindAllException()
    {
        $client = $this->getClient($this->getSampleResponse('401'));

        $feedRepository = new Feed(new Manager($client));
        $feedRepository->findAll();
    }

    public function feedProvider()
    {
        return array(
            array('1661')
        );
    }

    private function getSampleResponse($filename)
    {
        $filename = __DIR__ . '/../../ressources/response_samples/' . $filename . '.json';

        return file_get_contents($filename);
    }
}
