<?php

namespace PhraseanetSDK\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use PhraseanetSDK\Annotation\ApiField as ApiField;
use PhraseanetSDK\Annotation\ApiRelation as ApiRelation;

class QueryFacet 
{

    /**
     * @var string
     * @ApiField(bind_to="name", type="string")
     */
    protected $name;

    /**
     * @var QueryFacetValue[]|ArrayCollection
     * @ApiField(bind_to="values", type="relation")
     * @ApiRelation(type="one_to_many", target_entity="QueryFacetValue")
     */
    protected $values;

    /**
     * @param string $name
     * @param ArrayCollection|null $values
     */
    public function __construct($name = '', ArrayCollection $values = null)
    {
        $this->name = (string) $name;
        $this->values = $values ?: new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return ArrayCollection|QueryFacetValue[]
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @param ArrayCollection $values
     */
    public function setValues(ArrayCollection $values)
    {
        $this->values = $values;
    }
}
