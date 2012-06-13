<?php

namespace Test\Repository;

require_once 'Repository.php';

use Guzzle;
use Doctrine\Common\Collections\ArrayCollection;
use PhraseanetSDK\Client;
use PhraseanetSDK\Response;
use PhraseanetSDK\Repository\Metadatas;
use PhraseanetSDK\Tools\Entity\Manager;

class MetadatasTest extends Repository
{

    public function testFindAll()
    {
        $client = $this->getClient($this->getSampleResponse('repository/metadatas/findAll'));

        $metaRepository = new Metadatas(new Manager($client));
        $record = $this->getMock('\\PhraseanetSDK\\Entity\Record', array(), array(), '', false);
        $metas = $metaRepository->findAll($record);

        $this->assertTrue($metas instanceof ArrayCollection);
        $this->assertEquals(6, $metas->count());
    }

    /**
     * @expectedException PhraseanetSDK\Exception\ApiResponseException
     */
    public function testFindAllExcpetion()
    {
        $client = $this->getClient($this->getSampleResponse('401'));

        $metaRepository = new Metadatas(new Manager($client));
        $record = $this->getMock('\\PhraseanetSDK\\Entity\Record', array(), array(), '', false);
        $metaRepository->findAll($record);
    }

    private function getSampleResponse($filename)
    {
        $filename = __DIR__ . '/../../ressources/response_samples/' . $filename . '.json';

        return file_get_contents($filename);
    }
}
