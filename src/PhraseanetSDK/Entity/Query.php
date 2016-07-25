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

use Doctrine\Common\Collections\ArrayCollection;
use PhraseanetSDK\Annotation\ApiField as ApiField;
use PhraseanetSDK\Annotation\ApiRelation as ApiRelation;
use PhraseanetSDK\EntityManager;

class Query
{

    /**
     * @param EntityManager $entityManager
     * @param \stdClass $value
     * @return Query
     */
    public static function fromValue(EntityManager $entityManager, \stdClass $value)
    {
        return new self($entityManager, $value);
    }

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var \stdClass
     */
    protected $source;

    /**
     * @var QueryFacet[]|ArrayCollection
     */
    protected $facets;

    /**
     * @var QuerySuggestion[]|ArrayCollection
     */
    protected $suggestions;

    /**
     * @var Result|null
     */
    protected $results;

    /**
     * @param EntityManager $entityManager
     * @param \stdClass $source
     */
    public function __construct(EntityManager $entityManager, \stdClass $source)
    {
        $this->entityManager = $entityManager;
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
     * The offset start
     *
     * @return integer
     */
    public function getOffsetStart()
    {
        return $this->source->offset_start;
    }

    /**
     * The number item to retrieve
     *
     * @return integer
     */
    public function getPerPage()
    {
        return $this->source->per_page;
    }

    /**
     * Get the total result
     *
     * @return integer
     */
    public function getTotalResults()
    {
        return $this->source->total_results;
    }

    /**
     * Get errors as string
     *
     * @return string
     */
    public function getError()
    {
        return $this->source->error;
    }

    /**
     * Get warnings as string
     *
     * @return string
     */
    public function getWarning()
    {
        return $this->source->warning;
    }

    /**
     * Get the query time
     *
     * @return float
     */
    public function getQueryTime()
    {
        return $this->source->query_time;
    }

    /**
     * Get search indexes
     *
     * @return string
     */
    public function getSearchIndexes()
    {
        return $this->source->search_indexes;
    }

    /**
     * Get the query string
     *
     * @return string
     */
    public function getQuery()
    {
        return $this->source->query;
    }

    /**
     * Get query suggestions as a collection of QuerySuggestion
     * objects
     *
     * @return ArrayCollection
     */
    public function getSuggestions()
    {
        if (! isset($this->source->suggestions)) {
            $this->suggestions = new ArrayCollection();
        }

        return $this->suggestions ?: $this->suggestions = new ArrayCollection(QuerySuggestion::fromList(
            $this->source->suggestions
        ));
    }

    /**
     * @return ArrayCollection|QueryFacet[]
     */
    public function getFacets()
    {
        if (! isset($this->source->facets)) {
            $this->facets = new ArrayCollection();
        }

        return $this->facets ?: $this->facets = new ArrayCollection(QueryFacet::fromList($this->source->facets));
    }

    /**
     *
     * @return Result
     */
    public function getResults()
    {
        return $this->results ?: $this->results = Result::fromValue($this->entityManager, $this->source->results);
    }
}
