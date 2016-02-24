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
use PhraseanetSDK\Exception\RuntimeException;
use Doctrine\Common\Collections\ArrayCollection;
use PhraseanetSDK\EntityHydrator;

class RecordStatus extends AbstractRepository
{
    /**
     * Find All the status attached to the provided record
     *
     * @param  integer          $databoxId The record databox id
     * @param  integer          $recordId  the record id
     * @return ArrayCollection
     * @throws RuntimeException
     */
    public function findByRecord($databoxId, $recordId)
    {
        $response = $this->query('GET', sprintf('v1/records/%d/%d/status/', $databoxId, $recordId));

        if (true !== $response->hasProperty('status')) {
            throw new RuntimeException('Missing "status" property in response content');
        }

        return new ArrayCollection(\PhraseanetSDK\Entity\RecordStatus::fromList($response->getProperty('status')));
    }
}
