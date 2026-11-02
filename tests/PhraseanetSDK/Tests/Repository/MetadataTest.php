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

    public function testfindMetadataByRecordExcpetion()
    {
        $this->expectException('PhraseanetSDK\Exception\UnauthorizedException');

        $client = $this->getClient($this->getSampleResponse('401'), 401);
        $metaRepository = new Metadata(new EntityManager($client));
        $metaRepository->findByRecord(1, 1);
    }

    public function testfindMetadataByRecordRuntimeExcpetion()
    {
        $this->expectException('PhraseanetSDK\Exception\RuntimeException');

        $client = $this->getClient($this->getSampleResponse('empty'));
        $metaRepository = new Metadata(new EntityManager($client));
        $metaRepository->findByRecord(1, 1);
    }
}
