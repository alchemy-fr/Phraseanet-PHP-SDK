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

    public function testGetAdapter()
    {
        $adapter = $this->createAdapterMock();
        $em = new EntityManager($adapter);
        $this->assertSame($adapter, $em->getClient());
    }

    public function provideRepositories()
    {
        return array(
            array('Feed', 'PhraseanetSDK\Repository\Feed'),
            array('feed', 'PhraseanetSDK\Repository\Feed'),
            array('basket', 'PhraseanetSDK\Repository\Basket'),
            array('basketElement', 'PhraseanetSDK\Repository\BasketElement'),
            array('caption', 'PhraseanetSDK\Repository\Caption'),
            array('databoxTermsOfUse', 'PhraseanetSDK\Repository\DataboxTermsOfUse'),
            array('databox', 'PhraseanetSDK\Repository\Databox'),
            array('databoxCollection', 'PhraseanetSDK\Repository\DataboxCollection'),
            array('databoxDocumentStructure', 'PhraseanetSDK\Repository\DataboxDocumentStructure'),
            array('databoxStatus', 'PhraseanetSDK\Repository\DataboxStatus'),
            array('entry', 'PhraseanetSDK\Repository\Entry'),
            array('metadata', 'PhraseanetSDK\Repository\Metadata'),
            array('quarantine', 'PhraseanetSDK\Repository\Quarantine'),
            array('record', 'PhraseanetSDK\Repository\Record'),
            array('recordStatus', 'PhraseanetSDK\Repository\RecordStatus'),
            array('subdef', 'PhraseanetSDK\Repository\Subdef'),
            array('story', 'PhraseanetSDK\Repository\Story'),
        );
    }

    private function createAdapterMock()
    {
        return $this->getMockBuilder('PhraseanetSDK\Http\APIGuzzleAdapter')
            ->disableOriginalConstructor()
            ->getMock();
    }
}
