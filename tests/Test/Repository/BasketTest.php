<?php

namespace Test\Repository;

require_once 'Repository.php';

use Doctrine\Common\Collections\ArrayCollection;
use PhraseanetSDK\Client;
use PhraseanetSDK\Repository\Basket;
use PhraseanetSDK\EntityManager;

class BasketTest extends Repository
{

    public function testFindByRecord()
    {
        $client = $this->getClient($this->getSampleResponse('repository/basket/byRecord'));
        $basketRepository = new Basket(new EntityManager($client));
        $baskets = $basketRepository->findByRecord(1, 1);
        $this->assertIsCollection($baskets);
        foreach ($baskets as $basket) {
            $this->checkBasket($basket);
        }
    }

    /**
     * @expectedException PhraseanetSDK\Exception\RuntimeException
     */
    public function testFindByRecordRuntimeException()
    {
        $client = $this->getClient($this->getSampleResponse('empty'));
        $basketRepository = new Basket(new EntityManager($client));
        $basketRepository->findByRecord(1, 1);

    }

    public function testFindAll()
    {
        $client = $this->getClient($this->getSampleResponse('repository/basket/findAll'));
        $basketRepository = new Basket(new EntityManager($client));
        $baskets = $basketRepository->findAll();

        $this->assertTrue($baskets instanceof ArrayCollection);
        $this->assertGreaterThan(0, $baskets->count());

        foreach ($baskets as $basket) {
            $this->checkBasket($basket);
        }
    }

    /**
     * @expectedException PhraseanetSDK\Exception\RuntimeException
     */
    public function testFindAllRuntimeException()
    {
        $client = $this->getClient($this->getSampleResponse('empty'));
        $basketRepository = new Basket(new EntityManager($client));
        $basketRepository->findAll();
    }
}
