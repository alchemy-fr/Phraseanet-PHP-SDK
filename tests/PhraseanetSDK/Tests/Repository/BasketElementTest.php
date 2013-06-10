<?php

namespace PhraseanetSDK\Tests\Repository;

use PhraseanetSDK\Repository\BasketElement;
use PhraseanetSDK\EntityManager;

class BasketElementTest extends RepositoryTestCase
{

    public function testFindByBasket()
    {
        $client = $this->getClient($this->getSampleResponse('repository/basketElement/byBasket'));

        $basketElementRepository = new BasketElement(new EntityManager($client));
        $basketElements = $basketElementRepository->findByBasket(1);
        $this->assertIsCollection($basketElements);

        foreach ($basketElements as $basketElement) {
            $this->checkBasketElement($basketElement);
        }
    }

    /**
     * @expectedException PhraseanetSDK\Exception\RuntimeException
     */
    public function testFindByBasketRuntimeException()
    {
        $client = $this->getClient($this->getSampleResponse('empty'));
        $basketElementRepository = new BasketElement(new EntityManager($client));
        $basketElementRepository->findByBasket(1);
    }
}
