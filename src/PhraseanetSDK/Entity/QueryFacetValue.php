<?php

namespace PhraseanetSDK\Entity;

class QueryFacetValue
{
    /**
     * @param \stdClass[] $values
     * @return QueryFacetValue[]
     */
    public static function fromList(array $values)
    {
        $facetValues = array();

        foreach ($values as $value) {
            $facetValues[] = self::fromValue($value);
        }

        return $facetValues;
    }

    /**
     * @param \stdClass $value
     * @return QueryFacetValue
     */
    public static function fromValue(\stdClass $value)
    {
        return new self($value);
    }

    /**
     * @var \stdClass
     */
    protected $source;

    /**
     * @param \stdClass $source
     */
    public function __construct(\stdClass $source)
    {
        $this->source = $source;
    }

    /**
     * @return \stdClass
     */
    public function getRawData()
    {
        return $this->source;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->source->value;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->source->count;
    }

    /**
     * @return string
     */
    public function getQuery()
    {
        return $this->source->query;
    }
}
