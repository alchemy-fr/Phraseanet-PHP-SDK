<?php

namespace Test\Repository;

require_once 'Repository.php';

use PhraseanetSDK\Repository\Cgus;
use PhraseanetSDK\EntityManager;

class CgusTest extends Repository
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
