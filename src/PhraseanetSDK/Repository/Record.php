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
	 * @param int                          $pAPINumber API number (e.g. 3)
     * @return \PhraseanetSDK\Entity\Query object
     * @throws RuntimeException
     */
    public function search(array $parameters = array(), $pAPINumber = 1)
    {
		$response = $this->query('POST', 'v'.$pAPINumber.'/searchraw/', array(), array_merge(
            array('search_type' => 0),
            $parameters
        ));

        if ($response->isEmpty()) {
            throw new RuntimeException('Response content is empty');
        }

        $results = $res = $response->getResult();
        if ($pAPINumber == 3) {
            $results = new \stdClass();
            $results->results = new \stdClass();
            foreach ($res->results as $key => $r) {
                $results->results->records[$key] = $r->_source;
            }

            if (!isset($results->results->records)) {
                $results->results->records = [];
            }

            $results->results->stories = [];
            $results->facets = $res->facets;
            $results->count = $res->count;
            $results->total = $res->total;
            $results->limit = isset($res->limit) ? $res->limit : 10;  // TODO: just $res->limit after a phraseanet PR in searchraw
            $results->offset = isset($res->offset) ? $res->offset : 0;  // TODO: just $res->offset after a phraseanet PR
        }


        return Query::fromValue($this->em, $results);
    }
}
