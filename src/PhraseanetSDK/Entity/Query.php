<?php

namespace PhraseanetSDK\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class Query extends AbstractEntity implements EntityInterface
{
    protected $offsetStart;
    protected $perPage;
    protected $availableResults;
    protected $totalResults;
    protected $error;
    protected $warning;
    protected $queryTime;
    protected $searchIndexes;
    protected $query;

    /**
     *
     * @var Doctrine\Common\Collection\ArrayCollection
     */
    protected $suggestions;

    /**
     *
     * @var Doctrine\Common\Collection\ArrayCollection
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
     * Return the available results its depens of the search engine used
     * on the requested instance
     *
     * @return integer
     */
    public function getAvailableResults()
    {
        return $this->availableResults;
    }

    public function setAvailableResults($availableResults)
    {
        $this->availableResults = $availableResults;
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
     * Get query suggestions as a collection of PhraseanetSDK\Entity\QuerySuggestion
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
     * Get result as a collection of PhraseanetSDK\Entity\Record objects
     *
     * @return ArrayCollection
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
