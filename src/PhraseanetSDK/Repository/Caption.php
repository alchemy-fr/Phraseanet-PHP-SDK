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
use PhraseanetSDK\Entity\RecordCaption;
use PhraseanetSDK\Exception\NotFoundException;
use PhraseanetSDK\Exception\RuntimeException;
use Doctrine\Common\Collections\ArrayCollection;
use PhraseanetSDK\Exception\TokenExpiredException;
use PhraseanetSDK\Exception\UnauthorizedException;

class Caption extends AbstractRepository
{
    /**
     * Find All the caption metadata for the provided record
     *
     * @param integer $databoxId The record databox id
     * @param integer $recordId  The record id
     * @return ArrayCollection
     * @throws RuntimeException
     * @throws UnauthorizedException
     * @throws TokenExpiredException
     * @throws NotFoundException
     */
    public function findByRecord(int $databoxId, int $recordId): ArrayCollection
    {
        $response = $this->query('GET', sprintf('v1/records/%d/%d/caption/', $databoxId, $recordId));

        if (true !== $response->hasProperty('caption_metadatas')) {
            throw new RuntimeException('Missing "caption_metadatas" property in response content');
        }

        return new ArrayCollection(RecordCaption::fromList($response->getProperty('caption_metadatas')));
    }
}
