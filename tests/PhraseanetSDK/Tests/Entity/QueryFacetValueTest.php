<?php

namespace PhraseanetSDK\Tests\Entity;

use PhraseanetSDK\Entity\QueryFacetValue;

class QueryFacetValueTest extends \PHPUnit_Framework_TestCase
{

    public function testConstructorArgumentsAreAssignedToProperties()
    {
        $data = new \stdClass();
        $data->value = 'value';
        $data->count = 15;
        $data->query = 'query';

        $facetValue = new QueryFacetValue($data);

        $this->assertEquals('value', $facetValue->getValue());
        $this->assertEquals(15, $facetValue->getCount());
        $this->assertEquals('query', $facetValue->getQuery());
    }
}
