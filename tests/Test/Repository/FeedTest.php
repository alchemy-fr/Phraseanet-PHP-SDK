<?php

namespace Test\Repository;

use Guzzle;
use Doctrine\Common\Collections\ArrayCollection;
use PhraseanetSDK\Client;
use PhraseanetSDK\Response;
use PhraseanetSDK\Repository\Feed;
use PhraseanetSDK\Tools\Entity\Manager;

class FeedTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     * @dataProvider feedProvider
     */
    public function testFindById($idFeed)
    {
        $plugin = new Guzzle\Http\Plugin\MockPlugin();

        $plugin->addResponse(new Guzzle\Http\Message\Response(
                        200
                        , null
                        , $this->getSampleResponse('repository/feed/findById')
                )
        );

        $clientHttp = new Guzzle\Http\Client(
                        'http://my.domain.tld/api/v{{version}}',
                        array('version' => 1)
        );

        $clientHttp->getEventDispatcher()->addSubscriber($plugin);

        $client = new Client('http://my.domain.tld/', '123456', '654321', $clientHttp);
        
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
        $plugin = new Guzzle\Http\Plugin\MockPlugin();

        $plugin->addResponse(new Guzzle\Http\Message\Response(
                        200
                        , null
                        , $this->getSampleResponse('401')
                )
        );

        $clientHttp = new Guzzle\Http\Client(
                        'http://my.domain.tld/api/v{{version}}',
                        array('version' => 1)
        );

        $clientHttp->getEventDispatcher()->addSubscriber($plugin);

        $client = new Client('http://my.domain.tld/', '123456', '654321', $clientHttp);
        
        $feedRepository = new Feed(new Manager($client));

        $feedRepository->findById(44);
    }
    
    public function testFindAll()
    {
        $plugin = new Guzzle\Http\Plugin\MockPlugin();

        $plugin->addResponse(new Guzzle\Http\Message\Response(
                        200
                        , null
                        , $this->getSampleResponse('repository/feed/findAll')
                )
        );

        $clientHttp = new Guzzle\Http\Client(
                        'http://my.domain.tld/api/v{{version}}',
                        array('version' => 1)
        );

        $clientHttp->getEventDispatcher()->addSubscriber($plugin);

        $client = new Client('http://my.domain.tld/', '123456', '654321', $clientHttp);
        
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
        $plugin = new Guzzle\Http\Plugin\MockPlugin();

        $plugin->addResponse(new Guzzle\Http\Message\Response(
                        200
                        , null
                        , $this->getSampleResponse('401')
                )
        );

        $clientHttp = new Guzzle\Http\Client(
                        'http://my.domain.tld/api/v{{version}}',
                        array('version' => 1)
        );

        $clientHttp->getEventDispatcher()->addSubscriber($plugin);

        $client = new Client('http://my.domain.tld/', '123456', '654321', $clientHttp);
        
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