<?php

namespace PhraseanetSDK\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use PhraseanetSDK\Annotation\ApiField as ApiField;
use PhraseanetSDK\Annotation\ApiRelation as ApiRelation;

class QueryFacet 
{
    /**
     * @param \stdClass[] $values
     * @return QueryFacet[]
     */
    public static function fromList(array $values)
    {
        $facets = array();

        foreach ($values as $value) {
            $facets[] = self::fromValue($value);
        }

        return $facets;
    }

    /**
     * @param \stdClass $value
     * @return QueryFacet
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
     * @var QueryFacetValue[]|ArrayCollection
     */
    protected $values;

    /**
     * @param \stdClass $source
     */
    public function __construct(\stdClass $source)
    {
        $this->source = $source;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->source->name;
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
