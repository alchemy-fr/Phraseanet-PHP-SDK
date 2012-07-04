<?php

namespace PhraseanetSDK\Entity;

class QuerySuggestion extends AbstractEntity implements EntityInterface
{
    protected $value;
    protected $current;
    protected $hits;

    /**
     * Get the suggestion value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Tell whether the suggestion is current
     *
     * @return boolean
     */
    public function isCurrent()
    {
        return $this->current;
    }

    public function setCurrent($current)
    {
        $this->current = $current;
    }

    /**
     * Get the suggestion hit
     *
     * @return integer
     */
    public function getHits()
    {
        return $this->hits;
    }

    public function setHits($hits)
    {
        $this->hits = $hits;
    }

}
