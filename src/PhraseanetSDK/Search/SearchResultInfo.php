<?php

namespace PhraseanetSDK\Search;

use Doctrine\Common\Collections\ArrayCollection;
use PhraseanetSDK\Entity\QueryFacet;
use PhraseanetSDK\EntityManager;

class SearchResultInfo
{
    /**
     * @param EntityManager $entityManager
     * @param \stdClass $value
     * @return SearchResultInfo
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
     * @param EntityManager $entityManager
     * @param \stdClass $source
     */
    public function __construct(EntityManager $entityManager, \stdClass $source)
    {
        $this->entityManager = $entityManager;
        $this->source = $source;
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
     * @return ArrayCollection|QueryFacet[]
     */
    public function getFacets()
    {
        if (! isset($this->source->facets)) {
            $this->facets = new ArrayCollection();
        }

        return $this->facets ?: $this->facets = new ArrayCollection(QueryFacet::fromList($this->source->facets));
    }
}
