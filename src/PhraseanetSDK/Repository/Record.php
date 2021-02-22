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

use Doctrine\Common\Collections\ArrayCollection;
use PhraseanetSDK\AbstractRepository;
use PhraseanetSDK\Entity\Query as QueryEntity;
use PhraseanetSDK\Entity\Record as RecordEntity;
use PhraseanetSDK\Exception\NotFoundException;
use PhraseanetSDK\Exception\RuntimeException;
use PhraseanetSDK\Exception\TokenExpiredException;
use PhraseanetSDK\Exception\UnauthorizedException;

class Record extends AbstractRepository
{
    /**
     * Find the record by its id that belongs to the provided databox
     *
     * @param integer $databoxId    The record databox id
     * @param integer $recordId     The record id
     * @param boolean $disableCache Bypass cache when fetching a single record
     * @return RecordEntity
     * @throws RuntimeException
     * @throws UnauthorizedException
     * @throws TokenExpiredException
     * @throws NotFoundException
     */
    public function findById(int $databoxId, int $recordId, $disableCache = false): RecordEntity
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

        return RecordEntity::fromValue($response->getProperty('record'));
    }

    /**
     * Find records
     *
     * @param integer $offsetStart The offset
     * @param integer $perPage     The number of item per page
     * @return ArrayCollection
     * @throws RuntimeException
     * @throws UnauthorizedException
     * @throws TokenExpiredException
     * @throws NotFoundException
     */
    public function find(int $offsetStart, int $perPage): ArrayCollection
    {
        $response = $this->query('POST', 'v1/records/search/', array(), array(
            'query'        => 'all',
            'offset_start' => $offsetStart,
            'per_page'     => $perPage,
        ));

        if (true !== $response->hasProperty('results')) {
            throw new RuntimeException('Missing "results" property in response content');
        }

        return new ArrayCollection(RecordEntity::fromList(
            $response->getProperty('results')
        ));
    }

    /**
     * Search for records
     *
     * @param  array                       $parameters Query parameters
	 * @param int                          $pAPINumber API number (e.g. 3)
     * @return QueryEntity object
     * @throws RuntimeException
     * @throws UnauthorizedException
     * @throws TokenExpiredException
     * @throws NotFoundException
     */
    public function search(array $parameters = [], int $pAPINumber = 1): QueryEntity
    {
		$response = $this->query('POST', 'v'.$pAPINumber.'/search/', [], array_merge(
            ['search_type' => 0],
            $parameters
        ));

        if ($response->isEmpty()) {
            throw new RuntimeException('Response content is empty');
        }

        return QueryEntity::fromValue($this->em, $response->getResult());
    }
}
