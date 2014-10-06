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
use PhraseanetSDK\EntityHydrator;

class Record extends AbstractRepository
{
    /**
     * Find the record by its id that belongs to the provided databox
     *
     * @param  integer                      $databoxId The record databox id
     * @param  integer                      $recordId  The record id
     * @return \PhraseanetSDK\Entity\Record
     * @throws RuntimeException
     */
    public function findById($databoxId, $recordId)
    {
        $path = sprintf('records/%s/%s/', $databoxId, $recordId);

        $response = $this->query('GET', $path);

        if (true !== $response->hasProperty('record')) {
            throw new RuntimeException('Missing "record" property in response content');
        }

        return EntityHydrator::hydrate('record', $response->getProperty('record'), $this->em);
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
        $response = $this->query('POST', 'records/search/', array(), array(
            'query'        => 'all',
            'offset_start' => (int) $offsetStart,
            'per_page'     => (int) $perPage,
        ));

        if (true !== $response->hasProperty('results')) {
            throw new RuntimeException('Missing "results" property in response content');
        }

        $records = new ArrayCollection();

        foreach ($response->getProperty('results') as $recordData) {
            $records->add(EntityHydrator::hydrate('record', $recordData, $this->em));
        }

        return $records;
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
        $response = $this->query('POST', 'records/search/', array(), $parameters);

        if ($response->isEmpty()) {
            throw new RuntimeException('Response content is empty');
        }

        return EntityHydrator::hydrate('query', $response->getResult(), $this->em);
    }
}
