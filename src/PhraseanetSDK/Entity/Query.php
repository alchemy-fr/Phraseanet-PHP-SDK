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
use JMS\Serializer\Annotation\Expose as Expose;
use JMS\Serializer\Annotation\VirtualProperty as VirtualProperty;
use JMS\Serializer\Annotation\SerializedName as SerializedName;
use JMS\Serializer\Annotation\Type as Type;
use JMS\Serializer\Annotation\ExclusionPolicy as ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class Query
{
    /**
     * @Expose
     * @Type("integer")
     * @ApiField(bind_to="offset_start", type="int")
     */
    protected $offsetStart;
    /**
     * @Expose
     * @Type("integer")
     * @ApiField(bind_to="per_page", type="int")
     */
    protected $perPage;
    /**
     * @Expose
     * @Type("integer")
     * @ApiField(bind_to="total_results", type="int")
     */
    protected $totalResults;
    /**
     * @Expose
     * @Type("string")
     * @ApiField(bind_to="error", type="string")
     */
    protected $error;
    /**
     * @Expose
     * @Type("string")
     * @ApiField(bind_to="warning", type="string")
     */
    protected $warning;
    /**
     * @Expose
     * @ApiField(bind_to="query_time", type="float")
     */
    protected $queryTime;
    /**
     * @Expose
     * @Type("string")
     * @ApiField(bind_to="search_indexes", type="string")
     */
    protected $searchIndexes;
    /**
     * @Expose
     * @Type("string")
     * @ApiField(bind_to="query", type="string")
     */
    protected $query;
    /**
     * @Expose
     * @Type("ArrayCollection<PhraseanetSDK\Entity\QuerySuggestion>")
     * @ApiField(bind_to="suggestions", type="relation")
     * @ApiRelation(type="one_to_many", target_entity="QuerySuggestion")
     */
    protected $suggestions;
    /**
     * @Expose
     * @Type("PhraseanetSDK\Entity\Result")
     * @ApiField(bind_to="results", type="relation")
     * @ApiRelation(type="one_to_one", target_entity="Result")
     */
    protected $results;

    /**
     * The offset start
     *
     * @return integer
     */
    public function getOffsetStart()
    {
        return $this->offsetStart;
    }

    public function setOffsetStart($offsetStart)
    {
        $this->offsetStart = $offsetStart;
    }

    /**
     * The number item to retrieve
     *
     * @return integer
     */
    public function getPerPage()
    {
        return $this->perPage;
    }

    public function setPerPage($perPage)
    {
        $this->perPage = $perPage;
    }

    /**
     * Get the total result
     *
     * @return integer
     */
    public function getTotalResults()
    {
        return $this->totalResults;
    }

    public function setTotalResults($totalResults)
    {
        $this->totalResults = $totalResults;
    }

    /**
     * Get errors as string
     *
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    public function setError($error)
    {
        $this->error = $error;
    }

    /**
     * Get warnings as string
     *
     * @return string
     */
    public function getWarning()
    {
        return $this->warning;
    }

    public function setWarning($warning)
    {
        $this->warning = $warning;
    }

    /**
     * Get the query time
     *
     * @return float
     */
    public function getQueryTime()
    {
        return $this->queryTime;
    }

    public function setQueryTime($queryTime)
    {
        $this->queryTime = $queryTime;
    }

    /**
     * Get search indexes
     *
     * @return string
     */
    public function getSearchIndexes()
    {
        return $this->searchIndexes;
    }

    public function setSearchIndexes($searchIndexes)
    {
        $this->searchIndexes = $searchIndexes;
    }

    /**
     * Get the query string
     *
     * @return string
     */
    public function getQuery()
    {
        return $this->query;
    }

    public function setQuery($query)
    {
        $this->query = $query;
    }

    /**
     * Get query suggestions as a collection of QuerySuggestion
     * objects
     *
     * @return ArrayCollection
     */
    public function getSuggestions()
    {
        return $this->suggestions;
    }

    public function setSuggestions(ArrayCollection $suggestions)
    {
        $this->suggestions = $suggestions;
    }

    /**
     *
     * @return Result
     */
    public function getResults()
    {
        return $this->results;
    }

    public function setResults(Result $results)
    {
        $this->results = $results;
    }
}
