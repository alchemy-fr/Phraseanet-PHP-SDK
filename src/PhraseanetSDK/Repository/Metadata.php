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
use PhraseanetSDK\Entity\Metadata as MetadataEntity;
use PhraseanetSDK\Exception\NotFoundException;
use PhraseanetSDK\Exception\RuntimeException;
use PhraseanetSDK\Exception\TokenExpiredException;
use PhraseanetSDK\Exception\UnauthorizedException;

class Metadata extends AbstractRepository
{
    /**
     * Find All the metadata for the record provided in parameters
     *
     * @param integer $databoxId The databox id
     * @param integer $recordId  The record id
     * @return ArrayCollection
     * @throws RuntimeException
     * @throws UnauthorizedException
     * @throws TokenExpiredException
     * @throws NotFoundException
     */
    public function findByRecord(int $databoxId, int $recordId): ArrayCollection
    {
        $response = $this->query('GET', sprintf('v1/records/%d/%d/metadatas/', $databoxId, $recordId));

        if (true !== $response->hasProperty('record_metadatas')) {
            throw new RuntimeException('Missing "record_metadatas" property in response content');
        }

        return new ArrayCollection(MetadataEntity::fromList(
            $response->getProperty('record_metadatas')
        ));
    }
}
