<?php

namespace PhraseanetSDK;

use PhraseanetSDK\Repository\AbstractRepository;

class EntityManagerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers PhraseanetSDK\EntityManager::getRepository
     * @dataProvider classprovider
     */
    public function testGetRepository($type)
    {
        $client = $this->getMock(
            '\\\PhraseanetSDK\\Client'
            , array()
            , array()
            , ''
            , false
        );

        $em = new EntityManager($client);

        $repo = $em->getRepository($type);

        $this->assertTrue($repo instanceof AbstractRepository);
    }

    /**
     * @covers PhraseanetSDK\EntityManager::getEntity
     * @dataProvider classprovider
     */
    public function testGetEntity($type)
    {
        $client = $this->getMock(
            '\\\PhraseanetSDK\\Client'
            , array()
            , array()
            , ''
            , false
        );

        $em = new EntityManager($client);

        $entity = $em->getEntity($type);

        $this->assertTrue(is_object($entity));
    }

    /**
     * @covers PhraseanetSDK\EntityManager::__construct
     * @covers PhraseanetSDK\EntityManager::getClient
     */
    public function testGetClient()
    {
        $client = $this->getMock(
            '\\\PhraseanetSDK\\Client'
            , array()
            , array()
            , ''
            , false
        );

        $em = new EntityManager($client);

        $this->assertEquals($client, $em->getClient());
    }

    public function classProvider()
    {
        return array(
            array('feed')
        );
    }
}
