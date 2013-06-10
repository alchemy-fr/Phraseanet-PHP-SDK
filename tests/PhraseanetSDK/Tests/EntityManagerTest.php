<?php

namespace PhraseanetSDK\Tests;

use PhraseanetSDK\EntityManager;

class EntityManagerTest extends \PHPUnit_Framework_TestCase
{

    /** @dataProvider provideRepositories */
    public function testGetRepository($type, $instanceOf)
    {
        $em = new EntityManager($this->createAdapterMock());
        $this->assertInstanceOf($instanceOf, $em->getRepository($type));
    }

    /** @dataProvider provideEntities */
    public function testGetEntity($type, $instanceOf)
    {
        $em = new EntityManager($this->createAdapterMock());
        $this->assertInstanceOf($instanceOf, $em->getEntity($type));
    }

    public function testGetAdapter()
    {
        $adapter = $this->createAdapterMock();
        $em = new EntityManager($adapter);
        $this->assertSame($adapter, $em->getAdapter());
    }

    public function provideRepositories()
    {
        return array(
            array('Feed', 'PhraseanetSDK\Repository\Feed'),
            array('feed', 'PhraseanetSDK\Repository\Feed'),
            array('basket', 'PhraseanetSDK\Repository\Basket'),
            array('basketElement', 'PhraseanetSDK\Repository\BasketElement'),
            array('caption', 'PhraseanetSDK\Repository\Caption'),
            array('cgus', 'PhraseanetSDK\Repository\Cgus'),
            array('databox', 'PhraseanetSDK\Repository\Databox'),
            array('databoxCollection', 'PhraseanetSDK\Repository\DataboxCollection'),
            array('databoxDocumentStructure', 'PhraseanetSDK\Repository\DataboxDocumentStructure'),
            array('databoxStatus', 'PhraseanetSDK\Repository\DataboxStatus'),
            array('entry', 'PhraseanetSDK\Repository\Entry'),
            array('metadatas', 'PhraseanetSDK\Repository\Metadatas'),
            array('quarantine', 'PhraseanetSDK\Repository\Quarantine'),
            array('record', 'PhraseanetSDK\Repository\Record'),
            array('recordStatus', 'PhraseanetSDK\Repository\RecordStatus'),
            array('subdef', 'PhraseanetSDK\Repository\Subdef'),
            array('story', 'PhraseanetSDK\Repository\Story'),
        );
    }

    public function provideEntities()
    {
        return array(
            array('Feed', 'PhraseanetSDK\Entity\Feed'),
            array('feed', 'PhraseanetSDK\Entity\Feed'),
            array('basket', 'PhraseanetSDK\Entity\Basket'),
            array('basketElement', 'PhraseanetSDK\Entity\BasketElement'),
            array('cgus', 'PhraseanetSDK\Entity\Cgus'),
            array('databox', 'PhraseanetSDK\Entity\Databox'),
            array('databoxCollection', 'PhraseanetSDK\Entity\DataboxCollection'),
            array('databoxDocumentStructure', 'PhraseanetSDK\Entity\DataboxDocumentStructure'),
            array('databoxStatus', 'PhraseanetSDK\Entity\DataboxStatus'),
            array('feedEntry', 'PhraseanetSDK\Entity\FeedEntry'),
            array('metadatas', 'PhraseanetSDK\Entity\Metadatas'),
            array('quarantine', 'PhraseanetSDK\Entity\Quarantine'),
            array('record', 'PhraseanetSDK\Entity\Record'),
            array('recordStatus', 'PhraseanetSDK\Entity\RecordStatus'),
            array('subdef', 'PhraseanetSDK\Entity\Subdef'),
            array('story', 'PhraseanetSDK\Entity\Story'),
        );
    }

    private function createAdapterMock()
    {
        return $this->getMockBuilder('PhraseanetSDK\Http\APIGuzzleAdapter')
            ->disableOriginalConstructor()
            ->getMock();
    }
}
