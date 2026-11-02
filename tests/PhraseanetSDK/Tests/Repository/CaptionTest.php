<?php

namespace PhraseanetSDK\Tests\Repository;

use PhraseanetSDK\Repository\Caption;
use PhraseanetSDK\EntityManager;

class CaptionTest extends RepositoryTestCase
{

    public function testfindCaptionByRecord()
    {
        $client = $this->getClient($this->getSampleResponse('repository/recordCaption/byRecord'));
        $metaRepository = new Caption(new EntityManager($client));
        $metas = $metaRepository->findByRecord(1, 1);
        $this->assertIsCollection($metas);
        foreach ($metas as $meta) {
            $this->checkRecordCaption($meta);
        }
    }

    public function testfindCationByRecordExcpetion()
    {
        $this->expectException('PhraseanetSDK\Exception\UnauthorizedException');

        $client = $this->getClient($this->getSampleResponse('401'), 401);

        $metaRepository = new Caption(new EntityManager($client));
        $metaRepository->findByRecord(1, 1);
    }

    public function testfindCationByRecordRuntimeException()
    {
        $this->expectException('PhraseanetSDK\Exception\RuntimeException');

        $client = $this->getClient($this->getSampleResponse('empty'));

        $metaRepository = new Caption(new EntityManager($client));
        $metaRepository->findByRecord(1, 1);
    }
}
