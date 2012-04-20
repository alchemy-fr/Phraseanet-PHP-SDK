<?php

namespace Test\Tools\Entity;

use Alchemy\Sdk\Tools\Entity\Manager;
use Alchemy\Sdk\Tools\Repository\RepositoryAbstract;

class ManagerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider classprovider
     */
    public function testGetRepository($type)
    {
        $client = $this->getMock(
                '\\\PhraseanetApi'
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
     * @dataProvider classprovider
     */
    public function testGetEntity($type)
    {
        $client = $this->getMock(
                '\\\PhraseanetApi'
                , array()
                , array()
                , ''
                , false
        );

        $em = new Manager($client);

        $entity = $em->getEntity($type);

        $this->assertTrue(is_object($entity));
    }

    public function classProvider()
    {
        return array(
            array('feed')
        );
    }

}

