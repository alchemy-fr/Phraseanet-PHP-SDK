<?php

namespace PhraseanetSDK\Search;

use PhraseanetSDK\EntityManager;

class SearchResults
{

    public static function fromValue(EntityManager $entityManager, $resultType, \stdClass $value)
    {
        $searchResultInfo = SearchResultInfo::fromValue($entityManager, $value);
        $results = SearchResult::fromList($resultType, is_array($value->results) ? $value->results : []);

        return new self($searchResultInfo, $results);
    }

    /**
     * @var SearchResultInfo
     */
    private $resultMetadata;

    /**
     * @var SearchResult[]
     */
    private $results;

    /**
     * @param SearchResultInfo $searchResultInfo
     * @param array $results
     */
    public function __construct(SearchResultInfo $searchResultInfo, array $results)
    {
        $this->resultMetadata = $searchResultInfo;
        $this->results = $results;
    }

    /**
     * @return SearchResultInfo
     */
    public function getInfo()
    {
        return $this->resultMetadata;
    }

    /**
     * @return array|SearchResult[]
     */
    public function getResults()
    {
        return $this->results;
    }
}
