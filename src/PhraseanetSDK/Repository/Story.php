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

use PhraseanetSDK\Exception\RuntimeException;
use Doctrine\Common\Collections\ArrayCollection;

class Story extends AbstractRepository
{

    /**
     * Find the story by its id that belongs to the provided databox
     *
     * @param  integer                      $databoxId The record databox id
     * @param  integer                      $recordId  The record id
     * @return \PhraseanetSDK\Entity\Record
     * @throws RuntimeException
     */
    public function findById($databoxId, $recordId)
    {
        $path = sprintf('stories/%s/%s/', $databoxId, $recordId);

        $response = $this->query('GET', $path);

        if (true !== $response->hasProperty('story')) {
            throw new RuntimeException('Missing "story" property in response content');
        }

        return $this->em->HydrateEntity($this->em->getEntity('story'), $response->getProperty('story'));
    }

    /**
     * Find stories
     *
     * @param  integer          $offsetStart The offset
     * @param  integer          $perPage     The number of item per page
     * @return ArrayCollection
     * @throws RuntimeException
     */
    public function find($offsetStart, $perPage)
    {
        $response = $this->query('POST', 'search/', array(), array(
            'query'        => 'all',
            'search_type'  => 1,
            'offset_start' => (int) $offsetStart,
            'per_page'     => (int) $perPage
        ));

        if (true !== $response->hasProperty('results')) {
            throw new RuntimeException('Missing "results" property in response content');
        }

        $stories = new ArrayCollection();

        $results = $response->getProperty('results');

        if (isset($results->stories) && is_array($results->stories)) {
            foreach ($results->stories as $storyData) {
                $stories->add($this->em->hydrateEntity($this->em->getEntity('story'), $storyData));
            }
        }

        return $stories;
    }

    /**
     * Search for stories
     *
     * @param  array                       $parameters Query parameters
     * @return \PhraseanetSDK\Entity\Query object
     * @throws RuntimeException
     */
    public function search(array $parameters = array())
    {
        $response = $this->query('POST', 'search/', array(), array_merge(
            array('search_type' => 1), $parameters
        ));

        if ($response->isEmpty()) {
            throw new RuntimeException('Response content is empty');
        }

        return $this->em->hydrateEntity($this->em->getEntity('query'), $response->getResult());
    }
}
