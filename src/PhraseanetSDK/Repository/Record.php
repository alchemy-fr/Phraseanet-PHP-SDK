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

class Record extends AbstractRepository
{
    /**
     * Find the record by its id that belongs to the provided databox
     *
     * @param  integer                      $databoxId The record databox id
     * @param  integer                      $recordId  The record id
     * @param  boolean                      $disableCache Bypass cache when fetching a single record
     * @return \PhraseanetSDK\Entity\Record
     * @throws RuntimeException
     */
    public function findById($databoxId, $recordId, $disableCache = false)
    {
        $path = sprintf('v1/records/%s/%s/', $databoxId, $recordId);
        $query = [];

        if (true === $disableCache) {
            $query['t'] = time();
        }

        $response = $this->query('GET', $path, $query);

        if (true !== $response->hasProperty('record')) {
            throw new RuntimeException('Missing "record" property in response content');
        }

        return \PhraseanetSDK\Entity\Record::fromValue($response->getProperty('record'));
    }

    /**
     * Find records
     *
     * @param  integer          $offsetStart The offset
     * @param  integer          $perPage     The number of item per page
     * @return ArrayCollection
     * @throws RuntimeException
     */
    public function find($offsetStart, $perPage)
    {
        $response = $this->query('POST', 'v1/records/search/', array(), array(
            'query'        => 'all',
            'offset_start' => (int) $offsetStart,
            'per_page'     => (int) $perPage,
        ));

        if (true !== $response->hasProperty('results')) {
            throw new RuntimeException('Missing "results" property in response content');
        }

        return new ArrayCollection(\PhraseanetSDK\Entity\Record::fromList(
            $response->getProperty('results')
        ));
    }

    /**
     * Search for records
     *
     * @param  array                       $parameters Query parameters
     * @return \PhraseanetSDK\Entity\Query object
     * @throws RuntimeException
     */
    public function search(array $parameters = array())
    {
        $response = $this->query('POST', 'v1/search/', array(), array_merge(
            array('search_type' => 0),
            $parameters
        ));

        if ($response->isEmpty()) {
            throw new RuntimeException('Response content is empty');
        }

        return Query::fromValue($this->em, $response->getResult());
    }
}
