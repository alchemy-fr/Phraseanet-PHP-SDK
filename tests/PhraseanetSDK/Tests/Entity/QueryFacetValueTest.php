<?php

namespace PhraseanetSDK\Tests\Entity;

use PhraseanetSDK\Entity\QueryFacetValue;

class QueryFacetValueTest extends \PHPUnit_Framework_TestCase
{

    public function testFacetValuesAreInitializedWithDefaultValues()
    {
        $facetValue = new QueryFacetValue();

        $this->assertEquals('', $facetValue->getValue());
        $this->assertEquals(0, $facetValue->getCount());
        $this->assertEquals('', $facetValue->getQuery());
    }

    public function testConstructorArgumentsAreAssignedToProperties()
    {
        $facetValue = new QueryFacetValue('value', 15, 'query');

        $this->assertEquals('value', $facetValue->getValue());
        $this->assertEquals(15, $facetValue->getCount());
        $this->assertEquals('query', $facetValue->getQuery());
    }

    public function testPropertiesAreUpdatedViaSetters()
    {
        $facetValue = new QueryFacetValue('value', 15, 'query');

        $facetValue->setValue('modified-value');
        $this->assertEquals('modified-value', $facetValue->getValue());

        $facetValue->setCount(25);
        $this->assertEquals(25, $facetValue->getCount());

        $facetValue->setQuery('modified-query');
        $this->assertEquals('modified-query', $facetValue->getQuery());
    }
}
