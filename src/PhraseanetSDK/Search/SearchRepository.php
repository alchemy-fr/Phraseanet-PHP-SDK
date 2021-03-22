<?php

namespace PhraseanetSDK\Search;

use PhraseanetSDK\Exception\RuntimeException;
use PhraseanetSDK\AbstractRepository;

class SearchRepository extends AbstractRepository
{

    /**
     * Search for records or stories, returning only references to the matching entities.
     *
     * @param  mixed[] $parameters Query parameters
     * @return SearchResults object
     * @throws RuntimeException
     */
    public function search(array $parameters = array())
    {
        $parameters = array_merge([
            'search_type' => SearchResult::TYPE_RECORD
        ], $parameters);

        $response = $this->query('POST', 'v2/search/', array(), $parameters);

        if ($response->isEmpty()) {
            throw new RuntimeException('Response content is empty');
        }

        return SearchResults::fromValue($this->entityManager, $parameters['search_type'], $response->getResult());
    }
}
