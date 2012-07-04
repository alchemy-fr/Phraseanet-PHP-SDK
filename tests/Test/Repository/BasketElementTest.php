<?php

namespace Test\Repository;

require_once 'Repository.php';

use PhraseanetSDK\Client;
use PhraseanetSDK\Repository\BasketElement;
use PhraseanetSDK\Tools\Entity\Manager;

class BasketElementTest extends Repository
{

    public function testFindByBasket()
    {
        $client = $this->getClient($this->getSampleResponse('repository/basketElement/byBasket'));

        $basketElementRepository = new BasketElement(new Manager($client));
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
        $basketElementRepository = new BasketElement(new Manager($client));
        $basketElementRepository->findByBasket(1);
    }
}
