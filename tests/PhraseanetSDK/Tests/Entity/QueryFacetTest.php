<?php

namespace PhraseanetSDK\Tests\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use PhraseanetSDK\Entity\QueryFacet;

class QueryFacetTest extends \PHPUnit_Framework_TestCase
{

    public function testFacetsAreInitializedWithDefaultValues()
    {
        $facet = new QueryFacet(new \stdClass());

        $this->assertEquals('', $facet->getName());
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $facet->getValues());
        $this->assertCount(0, $facet->getValues());
    }

    public function testConstructorArgumentsAreAssignedToProperties()
    {
        $data = new \stdClass();
        $data->name = 'facet';
        $data->values = array();

        $facet = new QueryFacet($data);

        $this->assertEquals('facet', $facet->getName());
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $facet->getValues());
    }

    public function testPropertiesAreUpdatedViaSetters()
    {
        $data = new \stdClass();
        $data->name = 'facet';
        $data->values = array();

        $facet = new QueryFacet($data);

        $modified = new ArrayCollection();
        $facet->setValues($modified);

        $this->assertSame($modified, $facet->getValues());
    }
}
