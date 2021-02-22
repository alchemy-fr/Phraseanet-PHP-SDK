<?php

namespace PhraseanetSDK\Entity;

use stdClass;

class QueryFacetValue
{
    /**
     * @param stdClass[] $values
     * @return QueryFacetValue[]
     */
    public static function fromList(array $values): array
    {
        $facetValues = array();

        foreach ($values as $value) {
            $facetValues[] = self::fromValue($value);
        }

        return $facetValues;
    }

    /**
     * @param stdClass $value
     * @return QueryFacetValue
     */
    public static function fromValue(stdClass $value): QueryFacetValue
    {
        return new self($value);
    }

    /**
     * @var stdClass
     */
    protected $source;

    /**
     * @param stdClass $source
     */
    public function __construct(stdClass $source)
    {
        $this->source = $source;
    }

    /**
     * @return stdClass
     */
    public function getRawData(): stdClass
    {
        return $this->source;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->source->value;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->source->count;
    }

    /**
     * @return string
     */
    public function getQuery(): string
    {
        return $this->source->query;
    }
}
