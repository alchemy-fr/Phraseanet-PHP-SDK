<?php

namespace PhraseanetSDK\Tests;

use PhraseanetSDK\EntityManager;
use PhraseanetSDK\Http\APIGuzzleAdapter;

use PhraseanetSDK\Repository\Feed;
use PhraseanetSDK\Repository\Basket;
use PhraseanetSDK\Repository\BasketElement;
use PhraseanetSDK\Repository\Caption;
use PhraseanetSDK\Repository\DataboxTermsOfUse;
use PhraseanetSDK\Repository\Databox;
use PhraseanetSDK\Repository\DataboxCollection;
use PhraseanetSDK\Repository\DataboxDocumentStructure;
use PhraseanetSDK\Repository\DataboxStatus;
use PhraseanetSDK\Repository\Entry;
use PhraseanetSDK\Repository\Metadata;
use PhraseanetSDK\Repository\Quarantine;
use PhraseanetSDK\Repository\Record;
use PhraseanetSDK\Repository\RecordStatus;
use PhraseanetSDK\Repository\Subdef;
use PhraseanetSDK\Repository\Story;


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
        $this->assertSame($adapter, $em->getAdapter());
    }

    public function provideRepositories()
    {
        return array(
            array('Feed', Feed::class),
            array('feed', Feed::class),
            array('basket', Basket::class),
            array('basketElement', BasketElement::class),
            array('caption', Caption::class),
            array('databoxTermsOfUse', DataboxTermsOfUse::class),
            array('databox', Databox::class),
            array('databoxCollection', DataboxCollection::class),
            array('databoxDocumentStructure', DataboxDocumentStructure::class),
            array('databoxStatus', DataboxStatus::class),
            array('entry', Entry::class),
            array('metadata', Metadata::class),
            array('quarantine', Quarantine::class),
            array('record', Record::class),
            array('recordStatus', RecordStatus::class),
            array('subdef', Subdef::class),
            array('story', Story::class),
        );
    }

    private function createAdapterMock()
    {
        return $this->getMockBuilder(APIGuzzleAdapter::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
