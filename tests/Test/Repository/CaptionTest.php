<?php

namespace Test\Repository;

require_once 'Repository.php';

use PhraseanetSDK\Client;
use PhraseanetSDK\Repository\Caption;
use PhraseanetSDK\Tools\Entity\Manager;

class CaptionTest extends Repository
{

    public function testfindCaptionByRecord()
    {
        $client = $this->getClient($this->getSampleResponse('repository/recordCaption/byRecord'));
        $metaRepository = new Caption(new Manager($client));
        $metas = $metaRepository->findByRecord(1, 1);
        $this->assertIsCollection($metas);
        foreach ($metas as $meta) {
            $this->checkRecordCaption($meta);
        }
    }

    /**
     * @expectedException PhraseanetSDK\Exception\UnauthorizedException
     */
    public function testfindCationByRecordExcpetion()
    {
        $client = $this->getClient($this->getSampleResponse('401'), 401);

        $metaRepository = new Caption(new Manager($client));
        $metaRepository->findByRecord(1, 1);
    }

    /**
     * @expectedException PhraseanetSDK\Exception\RuntimeException
     */
    public function testfindCationByRecordRuntimeException()
    {
        $client = $this->getClient($this->getSampleResponse('empty'));

        $metaRepository = new Caption(new Manager($client));
        $metaRepository->findByRecord(1, 1);
    }
}
