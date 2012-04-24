<?php

namespace Test\Repository;

use Guzzle;
use Doctrine\Common\Collections\ArrayCollection;
use PhraseanetSDK\Client;
use PhraseanetSDK\Response;
use PhraseanetSDK\Repository\Subdef;
use PhraseanetSDK\Tools\Entity\Manager;

class SubdefTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider subdefNameProvider
     */
    public function testFindByName($name)
    {
        $plugin = new Guzzle\Http\Plugin\MockPlugin();

        $plugin->addResponse(new Guzzle\Http\Message\Response(
                        200
                        , null
                        , $this->getSampleResponse('repository/subdef/findAll')
                )
        );

        $clientHttp = new Guzzle\Http\Client(
                        'http://my.domain.tld/api/v{{version}}',
                        array('version' => 1)
        );

        $clientHttp->getEventDispatcher()->addSubscriber($plugin);

        $client = new Client('http://my.domain.tld/', '123456', '654321', $clientHttp);
        
        $subdefRepository = new Subdef(new Manager($client));

        $record = $this->getMock('\\PhraseanetSDK\\Entity\Record');
        
        $subdef = $subdefRepository->findByName($record, $name);

        $this->assertTrue($subdef instanceof \PhraseanetSDK\Entity\Subdef);
    }
    
    /**
     * @expectedException PhraseanetSDK\Exception\ApiResponseException
     */
    public function testFindByNameException()
    {
        $plugin = new Guzzle\Http\Plugin\MockPlugin();

        $plugin->addResponse(new Guzzle\Http\Message\Response(
                        200
                        , null
                        , $this->getSampleResponse('repository/subdef/findAll')
                )
        );

        $clientHttp = new Guzzle\Http\Client(
                        'http://my.domain.tld/api/v{{version}}',
                        array('version' => 1)
        );

        $clientHttp->getEventDispatcher()->addSubscriber($plugin);

        $client = new Client('http://my.domain.tld/', '123456', '654321', $clientHttp);
        
        $subdefRepository = new Subdef(new Manager($client));

        $record = $this->getMock('\\PhraseanetSDK\\Entity\Record');
        
        $subdefRepository->findByName($record, 'unknowName');
    }
    
    public function testFindAll()
    {
        $plugin = new Guzzle\Http\Plugin\MockPlugin();

        $plugin->addResponse(new Guzzle\Http\Message\Response(
                        200
                        , null
                        , $this->getSampleResponse('repository/subdef/findAll')
                )
        );

        $clientHttp = new Guzzle\Http\Client(
                        'http://my.domain.tld/api/v{{version}}',
                        array('version' => 1)
        );

        $clientHttp->getEventDispatcher()->addSubscriber($plugin);

        $client = new Client('http://my.domain.tld/', '123456', '654321', $clientHttp);
        
        $subdefRepository = new Subdef(new Manager($client));

        $record = $this->getMock('\\PhraseanetSDK\\Entity\Record');
        
        $subdefs = $subdefRepository->findAll($record);

        $this->assertTrue($subdefs instanceof ArrayCollection);
        $this->assertEquals(5, $subdefs->count());
    }
    
    /**
     * @expectedException PhraseanetSDK\Exception\ApiResponseException
     */
    public function testFindAllExcpetion()
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
        
        $subdefRepository = new Subdef(new Manager($client));

        $record = $this->getMock('\\PhraseanetSDK\\Entity\Record');
        
        $subdefRepository->findAll($record);

    }
    
     public function subdefNameProvider()
    {
        return array(
            array('preview'),
            array('thumbnail'),
            array('document'),
            array('preview_api'),
            array('thumbnailgif')
        );
    }
    
    private function getSampleResponse($filename)
    {
        $filename = __DIR__ . '/../../ressources/response_samples/' . $filename . '.json';
        return file_get_contents($filename);
    }
}