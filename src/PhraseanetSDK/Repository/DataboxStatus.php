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
use PhraseanetSDK\Entity\DataboxStatus as DataboxStatusEntity;
use PhraseanetSDK\Exception\NotFoundException;
use PhraseanetSDK\Exception\RuntimeException;
use PhraseanetSDK\Exception\TokenExpiredException;
use PhraseanetSDK\Exception\UnauthorizedException;

class DataboxStatus extends AbstractRepository
{
    /**
     * The status of the desired databox
     *
     * @param integer $databoxId the databox id
     * @return ArrayCollection
     * @throws RuntimeException
     * @throws UnauthorizedException
     * @throws TokenExpiredException
     * @throws NotFoundException
     */
    public function findByDatabox(int $databoxId): ArrayCollection
    {
        $response = $this->query('GET', sprintf('v1/databoxes/%d/status/', $databoxId));

        if (true !== $response->hasProperty('status')) {
            throw new RuntimeException('Missing "status" property in response content');
        }

        return new ArrayCollection(DataboxStatusEntity::fromList(
            $response->getProperty('status')
        ));
    }
}
