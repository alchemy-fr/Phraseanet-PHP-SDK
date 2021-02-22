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
use PhraseanetSDK\Entity\DataboxDocumentStructure as DataboxDocumentStructureEntity;
use PhraseanetSDK\Exception\NotFoundException;
use PhraseanetSDK\Exception\RuntimeException;
use PhraseanetSDK\Exception\TokenExpiredException;
use PhraseanetSDK\Exception\UnauthorizedException;

class DataboxDocumentStructure extends AbstractRepository
{
    /**
     * Find All structure document of the desired databox
     *
     * @param integer $databoxId The databox id
     * @return ArrayCollection
     * @throws RuntimeException
     * @throws UnauthorizedException
     * @throws TokenExpiredException
     * @throws NotFoundException
     */
    public function findByDatabox(int $databoxId): ArrayCollection
    {
        $response = $this->query('GET', sprintf('v1/databoxes/%d/metadatas/', $databoxId));

        if (true !== $response->hasProperty('document_metadatas')) {
            throw new RuntimeException('Missing "document_metadatas_structure" property in response content');
        }

        return new ArrayCollection(DataboxDocumentStructureEntity::fromList(
            $response->getProperty('document_metadatas')
        ));
    }
}
