<?php

namespace PhraseanetSDK\Tests\Repository;

require_once 'Repository.php';

use PhraseanetSDK\Client;
use PhraseanetSDK\Repository\DataboxDocumentStructure;
use PhraseanetSDK\EntityManager;

class DocumentStructureTest extends Repository
{
    public function testFindByDatabox()
    {
        $client = $this->getClient($this->getSampleResponse('repository/documentStructure/findAll'));
        $databoxMetadatasRepository = new DataboxDocumentStructure(new EntityManager($client));
        $databoxMetadatas = $databoxMetadatasRepository->findByDatabox(1);

        $this->assertEquals(27, $databoxMetadatas->count());

        foreach ($databoxMetadatas as $metadatas) {
            $this->checkDataboxStructure($metadatas);
        }
    }

    /**
     * @expectedException PhraseanetSDK\Exception\UnauthorizedException
     */
    public function testFindByDataboxException()
    {
        $client = $this->getClient($this->getSampleResponse('401'), 401);
        $collectionRepository = new DataboxDocumentStructure(new EntityManager($client));
        $collectionRepository->findByDatabox(1);
    }

    /**
     * @expectedException PhraseanetSDK\Exception\RuntimeException
     */
    public function testFindByDataboxRuntimeException()
    {
        $client = $this->getClient($this->getSampleResponse('empty'));
        $collectionRepository = new DataboxDocumentStructure(new EntityManager($client));
        $collectionRepository->findByDatabox(1);
    }

}
