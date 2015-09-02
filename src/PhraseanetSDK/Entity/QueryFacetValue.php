<?php

namespace PhraseanetSDK\Entity;

use PhraseanetSDK\Annotation\ApiField as ApiField;

class QueryFacetValue 
{
    /**
     * @var string
     * @ApiField(bind_to="value", type="string")
     */
    protected $value;

    /**
     * @var int
     * @ApiField(bind_to="count", type="int")
     */
    protected $count;

    /**
     * @var string
     * @ApiField(bind_to="query", type="string")
     */
    protected $query;

    public function __construct($value = '', $count = 0, $query = '')
    {
        $this->value = (string) $value;
        $this->count = (int) $count;
        $this->query = (string) $query;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param int $count
     */
    public function setCount($count)
    {
        $this->count = $count;
    }

    /**
     * @return string
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param string $query
     */
    public function setQuery($query)
    {
        $this->query = $query;
    }
}
