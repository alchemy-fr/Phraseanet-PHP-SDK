<?php

namespace PhraseanetSDK\Tests\Repository;

use PhraseanetSDK\Repository\Metadata;
use PhraseanetSDK\EntityManager;

class MetadataTest extends RepositoryTestCase
{

    public function testfindMetadataByRecord()
    {
        $client = $this->getClient($this->getSampleResponse('repository/metadatas/byRecord'));
        $metaRepository = new Metadata(new EntityManager($client));
        $metas = $metaRepository->findByRecord(1, 1);
        $this->assertIsCollection($metas);
        foreach ($metas as $meta) {
            $this->checkMetadata($meta);
        }
    }

    /**
     * @expectedException PhraseanetSDK\Exception\UnauthorizedException
     */
    public function testfindMetadataByRecordExcpetion()
    {
        $client = $this->getClient($this->getSampleResponse('401'), 401);
        $metaRepository = new Metadata(new EntityManager($client));
        $metaRepository->findByRecord(1, 1);
    }

    /**
     * @expectedException PhraseanetSDK\Exception\RuntimeException
     */
    public function testfindMetadataByRecordRuntimeExcpetion()
    {
        $client = $this->getClient($this->getSampleResponse('empty'));
        $metaRepository = new Metadata(new EntityManager($client));
        $metaRepository->findByRecord(1, 1);
    }

}
