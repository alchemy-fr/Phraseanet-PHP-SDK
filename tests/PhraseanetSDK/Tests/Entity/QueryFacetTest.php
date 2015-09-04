<?php

namespace PhraseanetSDK\Tests\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use PhraseanetSDK\Entity\QueryFacet;

class QueryFacetTest extends \PHPUnit_Framework_TestCase
{

    public function testFacetsAreInitializedWithDefaultValues()
    {
        $facet = new QueryFacet();

        $this->assertEquals('', $facet->getName());
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $facet->getValues());
        $this->assertCount(0, $facet->getValues());
    }

    public function testConstructorArgumentsAreAssignedToProperties()
    {
        $collection = new ArrayCollection();
        $facet = new QueryFacet('facet', $collection);

        $this->assertEquals('facet', $facet->getName());
        $this->assertSame($collection, $facet->getValues());
    }

    public function testPropertiesAreUpdatedViaSetters()
    {
        $collection = new ArrayCollection();
        $facet = new QueryFacet('facet', $collection);

        $facet->setName('modified-facet');

        $this->assertEquals('modified-facet', $facet->getName());

        $modified = new ArrayCollection();
        $facet->setValues($modified);

        $this->assertSame($modified, $facet->getValues());
    }
}
