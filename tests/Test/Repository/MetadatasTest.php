<?php

namespace Test\Repository;

use Guzzle;
use Doctrine\Common\Collections\ArrayCollection;
use PhraseanetSDK\Client;
use PhraseanetSDK\Response;
use PhraseanetSDK\Repository\Metadatas;
use PhraseanetSDK\Tools\Entity\Manager;

class MetadatasTest extends \PHPUnit_Framework_TestCase
{
    public function testFindAll()
    {
        $plugin = new Guzzle\Http\Plugin\MockPlugin();

        $plugin->addResponse(new Guzzle\Http\Message\Response(
                        200
                        , null
                        , $this->getSampleResponse('repository/metadatas/findAll')
                )
        );

        $clientHttp = new Guzzle\Http\Client(
                        'http://my.domain.tld/api/v{{version}}',
                        array('version' => 1)
        );

        $clientHttp->getEventDispatcher()->addSubscriber($plugin);

        $client = new Client('http://my.domain.tld/', '123456', '654321', $clientHttp);
        
        $metaRepository = new Metadatas(new Manager($client));

        $record = $this->getMock('\\PhraseanetSDK\\Entity\Record');
        
        $metas = $metaRepository->findAll($record);

        $this->assertTrue($metas instanceof ArrayCollection);
        $this->assertEquals(6, $metas->count());
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
        
        $metaRepository = new Metadatas(new Manager($client));

        $record = $this->getMock('\\PhraseanetSDK\\Entity\Record');
        
        $metaRepository->findAll($record);

    }
    
    private function getSampleResponse($filename)
    {
        $filename = __DIR__ . '/../../ressources/response_samples/' . $filename . '.json';
        return file_get_contents($filename);
    }
}