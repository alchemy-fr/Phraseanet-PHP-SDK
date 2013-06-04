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
        $response = $this->query('GET', sprintf('/records/%d/%d/status/', $databoxId, $recordId));

        $statusCollection = new ArrayCollection();

        if (true !== $response->hasProperty('status')) {
            throw new RuntimeException('Missing "status" property in response content');
        }

        foreach ($response->getProperty('status') as $statusData) {
            $statusCollection->add($this->em->hydrateEntity($this->em->getEntity('recordStatus'), $statusData));
        }

        return $statusCollection;
    }
}
