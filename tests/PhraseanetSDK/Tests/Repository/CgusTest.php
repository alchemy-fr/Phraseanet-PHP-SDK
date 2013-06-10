<?php

namespace PhraseanetSDK\Tests\Repository;

use PhraseanetSDK\Repository\Cgus;
use PhraseanetSDK\EntityManager;

class CgusTest extends RepositoryTestCase
{

    public function testfindCgus()
    {
        $client = $this->getClient($this->getSampleResponse('repository/cgus/list'));
        $metaRepository = new Cgus(new EntityManager($client));
        $cgus = $metaRepository->findByDatabox(1);
        $this->assertIsCollection($cgus);
        foreach ($cgus as $cgu) {
            $this->checkCgus($cgu);
        }
    }
}
