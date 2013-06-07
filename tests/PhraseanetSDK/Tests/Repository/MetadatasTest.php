<?php

namespace PhraseanetSDK\Tests\Repository;

use PhraseanetSDK\Repository\Metadatas;
use PhraseanetSDK\EntityManager;

class MetadatasTest extends RepositoryTestCase
{

    public function testfindMetadatasByRecord()
    {
        $client = $this->getClient($this->getSampleResponse('repository/metadatas/byRecord'));
        $metaRepository = new Metadatas(new EntityManager($client));
        $metas = $metaRepository->findByRecord(1, 1);
        $this->assertIsCollection($metas);
        foreach ($metas as $meta) {
            $this->checkMetadatas($meta);
        }
    }

    /**
     * @expectedException PhraseanetSDK\Exception\UnauthorizedException
     */
    public function testfindMetadatasByRecordExcpetion()
    {
        $client = $this->getClient($this->getSampleResponse('401'), 401);
        $metaRepository = new Metadatas(new EntityManager($client));
        $metaRepository->findByRecord(1, 1);
    }

    /**
     * @expectedException PhraseanetSDK\Exception\RuntimeException
     */
    public function testfindMetadatasByRecordRuntimeExcpetion()
    {
        $client = $this->getClient($this->getSampleResponse('empty'));
        $metaRepository = new Metadatas(new EntityManager($client));
        $metaRepository->findByRecord(1, 1);
    }

}
