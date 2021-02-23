<?php

namespace PhraseanetSDK\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use PhraseanetSDK\Annotation\ApiField as ApiField;
use PhraseanetSDK\Annotation\ApiRelation as ApiRelation;
use stdClass;

class QueryFacet
{
    /**
     * @param stdClass[] $values
     * @return QueryFacet[]
     */
    public static function fromList(array $values): array
    {
        $facets = array();

        foreach ($values as $value) {
            $facets[] = self::fromValue($value);
        }

        return $facets;
    }

    /**
     * @param stdClass $value
     * @return QueryFacet
     */
    public static function fromValue(stdClass $value): QueryFacet
    {
        return new self($value);
    }

    /**
     * @var stdClass
     */
    protected $source;

    /**
     * @var QueryFacetValue[]|ArrayCollection
     */
    protected $values;

    /**
     * @param stdClass|null $source
     */
    public function __construct(stdClass $source = null)
    {
        $this->source = $source ?: new stdClass();
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
    public function getName(): string
    {
        return isset($this->source->name) ? $this->source->name : '';
    }

    /**
     * @return ArrayCollection|QueryFacetValue[]
     */
    public function getValues()
    {
        if (! isset($this->source->values)) {
            $this->values = new ArrayCollection();
        }

        return $this->values ?: $this->values = new ArrayCollection(QueryFacetValue::fromList($this->source->values));
    }

    /**
     * @param ArrayCollection|QueryFacetValue[] $values
     */
    public function setValues($values)
    {
        if (is_array($values)) {
            $values = new ArrayCollection($values);
        }

        $this->values = $values;
    }
}
