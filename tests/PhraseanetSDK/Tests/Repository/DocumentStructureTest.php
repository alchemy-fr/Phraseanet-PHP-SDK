<?php

namespace PhraseanetSDK\Tests\Repository;

use PhraseanetSDK\Repository\DataboxDocumentStructure;
use PhraseanetSDK\EntityManager;

class DocumentStructureTest extends RepositoryTestCase
{
    public function testFindByDatabox()
    {
        $client = $this->getClient($this->getSampleResponse('repository/documentStructure/findAll'));
        $databoxMetadatasRepository = new DataboxDocumentStructure(new EntityManager($client));
        $databoxMetadatas = $databoxMetadatasRepository->findByDatabox(1);

        $this->assertEquals(33, $databoxMetadatas->count());

        foreach ($databoxMetadatas as $metadatas) {
            $this->checkDataboxStructure($metadatas);
        }
    }

    public function testFindByDataboxException()
    {
        $this->expectException('PhraseanetSDK\Exception\UnauthorizedException');

        $client = $this->getClient($this->getSampleResponse('401'), 401);
        $collectionRepository = new DataboxDocumentStructure(new EntityManager($client));
        $collectionRepository->findByDatabox(1);
    }

    public function testFindByDataboxRuntimeException()
    {
        $this->expectException('PhraseanetSDK\Exception\RuntimeException');

        $client = $this->getClient($this->getSampleResponse('empty'));
        $collectionRepository = new DataboxDocumentStructure(new EntityManager($client));
        $collectionRepository->findByDatabox(1);
    }
}
