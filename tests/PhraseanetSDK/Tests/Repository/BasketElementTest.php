<?php

namespace PhraseanetSDK\Tests\Repository;

use PhraseanetSDK\Repository\BasketElement;
use PhraseanetSDK\EntityManager;

class BasketElementTest extends RepositoryTestCase
{

    protected function checkBasketElement($basketElement)
    {
        $this->assertTrue($basketElement instanceof \PhraseanetSDK\Entity\BasketElement);
        /* @var $basketElement \PhraseanetSDK\Entity\BasketElement */

        $this->assertNotNull($basketElement->getOrder());
        $this->assertInternalType('integer', $basketElement->getOrder());
        $this->assertNotNull($basketElement->getId());
        $this->assertInternalType('integer', $basketElement->getId());
        $this->assertNotNull($basketElement->isValidationItem());
        $this->assertInternalType('boolean', $basketElement->isValidationItem());
        $this->assertNotNull($record = $basketElement->getRecord());
        $this->checkRecord($record);

        if ($basketElement->isValidationItem()) {
            $this->assertNotNull($choices = $basketElement->getValidationChoices());
            $this->assertIsCollection($choices);

            foreach ($choices as $choice) {
                $this->checkValidationChoice($choice);
            }

            $this->assertTrue(in_array($basketElement->getAgreement(), array(null, true, false)));
            $this->assertNotNull($basketElement->getNote());
            $this->assertInternalType('integer', $basketElement->getNote());
        }
    }

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
     * @expectedException \PhraseanetSDK\Exception\RuntimeException
     */
    public function testFindByBasketRuntimeException()
    {
        $client = $this->getClient($this->getSampleResponse('empty'));
        $basketElementRepository = new BasketElement(new EntityManager($client));
        $basketElementRepository->findByBasket(1);
    }
}
