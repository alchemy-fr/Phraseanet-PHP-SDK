<?php

/*
 * This file is part of Phraseanet SDK.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhraseanetSDK\Repository;

use PhraseanetSDK\AbstractRepository;
use PhraseanetSDK\Entity\Query;
use PhraseanetSDK\Exception\RuntimeException;
use Doctrine\Common\Collections\ArrayCollection;
use PhraseanetSDK\Search\SearchResult;

class Story extends AbstractRepository
{
    /**
     * Find the story by its id that belongs to the provided databox
     *
     * @param  integer $databoxId The record databox id
     * @param  integer $recordId  The record id
     * @return \PhraseanetSDK\Entity\Story
     * @throws RuntimeException
     */
    public function findById($databoxId, $recordId)
    {
        $path = sprintf('v1/stories/%s/%s/', $databoxId, $recordId);

        $response = $this->query('GET', $path);

        if (true !== $response->hasProperty('story')) {
            throw new RuntimeException('Missing "story" property in response content');
        }

        return \PhraseanetSDK\Entity\Story::fromValue($this->entityManager, $response->getProperty('story'));
    }

    /**
     * Find stories
     *
     * @param  integer $offsetStart The offset
     * @param  integer $perPage The number of item per page
     * @return ArrayCollection|Story[]
     * @throws RuntimeException
     */
    public function find($offsetStart, $perPage)
    {
        $response = $this->query('POST', 'v1/search/', array(), array(
            'query'        => '',
            'search_type'  => SearchResult::TYPE_STORY,
            'offset_start' => (int) $offsetStart,
            'per_page'     => (int) $perPage,
        ));

        if (true !== $response->hasProperty('results')) {
            throw new RuntimeException('Missing "results" property in response content');
        }

        return Query::fromValue($this->entityManager, $response->getResult())->getResults()->getStories();
    }

    /**
     * Search for stories
     *
     * @param  array $parameters Query parameters
	 * @param int $pAPINumber API number (e.g. 3)
     * @return \PhraseanetSDK\Entity\Query object
     * @throws RuntimeException
     */
    public function search(array $parameters = array(), $pAPINumber = 1)
    {
        $response = $this->query('POST', 'v'.$pAPINumber.'/search/', array(), array_merge(
            $parameters,
			array('search_type' => SearchResult::TYPE_STORY)
        ));

        if ($response->isEmpty()) {
            throw new RuntimeException('Response content is empty');
        }

        return Query::fromValue($this->entityManager, $response->getResult());
    }
}
