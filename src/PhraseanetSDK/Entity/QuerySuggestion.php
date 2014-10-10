<?php

/*
 * This file is part of Phraseanet SDK.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhraseanetSDK\Entity;

use PhraseanetSDK\Annotation\ApiField as ApiField;

class QuerySuggestion
{
    /**
     * @ApiField(bind_to="value", type="string")
     */
    protected $value;
    /**
     * @ApiField(bind_to="current", type="string")
     */
    protected $current;
    /**
     * @ApiField(bind_to="hits", type="int")
     */
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
     * @return Boolean
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
