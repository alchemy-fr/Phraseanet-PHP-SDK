<?php

namespace Test\Tools\Entity;

use PhraseanetSDK\Tools\Entity\Manager;
use PhraseanetSDK\Repository\RepositoryAbstract;

class ManagerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers PhraseanetSDK\Tools\Entity\Manager::getRepository
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

        $em = new Manager($client);

        $repo = $em->getRepository($type);

        $this->assertTrue($repo instanceof RepositoryAbstract);
    }

    /**
     * @covers PhraseanetSDK\Tools\Entity\Manager::getEntity
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

        $em = new Manager($client);

        $entity = $em->getEntity($type);

        $this->assertTrue(is_object($entity));
    }

    /**
     * @covers PhraseanetSDK\Tools\Entity\Manager::__construct
     * @covers PhraseanetSDK\Tools\Entity\Manager::getClient
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

        $em = new Manager($client);

        $this->assertEquals($client, $em->getClient());
    }

    public function classProvider()
    {
        return array(
            array('feed')
        );
    }

}

