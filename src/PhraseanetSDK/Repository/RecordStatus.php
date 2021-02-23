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
use PhraseanetSDK\Entity\RecordStatus as RecordStatusEntity;
use PhraseanetSDK\Exception\NotFoundException;
use PhraseanetSDK\Exception\RuntimeException;
use PhraseanetSDK\Exception\TokenExpiredException;
use PhraseanetSDK\Exception\UnauthorizedException;

class RecordStatus extends AbstractRepository
{
    /**
     * Find All the status attached to the provided record
     *
     * @param integer $databoxId The record databox id
     * @param integer $recordId  the record id
     * @return ArrayCollection
     * @throws RuntimeException
     * @throws UnauthorizedException
     * @throws TokenExpiredException
     * @throws NotFoundException
     */
    public function findByRecord(int $databoxId, int $recordId): ArrayCollection
    {
        $response = $this->query('GET', sprintf('v1/records/%d/%d/status/', $databoxId, $recordId));

        if (true !== $response->hasProperty('status')) {
            throw new RuntimeException('Missing "status" property in response content');
        }

        return new ArrayCollection(RecordStatusEntity::fromList($response->getProperty('status')));
    }
}
