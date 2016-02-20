<?php

namespace PhraseanetSDK\Tests\Repository;

use PhraseanetSDK\Repository\DataboxTermsOfUse;
use PhraseanetSDK\EntityManager;

class DataboxTermsOfuseTest extends RepositoryTestCase
{

    public function testfindTOU()
    {
        $client = $this->getClient($this->getSampleResponse('repository/cgus/list'));
        $metaRepository = new DataboxTermsOfUse(new EntityManager($client));
        $terms = $metaRepository->findByDatabox(1);
        $this->assertIsCollection($terms);
        foreach ($terms as $term) {
            $this->checkTermsOfUse($term);
        }
    }
}
