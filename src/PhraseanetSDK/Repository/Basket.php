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
use PhraseanetSDK\Entity\Basket as BasketEntity;
use PhraseanetSDK\Exception\NotFoundException;
use PhraseanetSDK\Exception\RuntimeException;
use PhraseanetSDK\Exception\TokenExpiredException;
use PhraseanetSDK\Exception\UnauthorizedException;

class Basket extends AbstractRepository
{
    /**
     * Find all baskets that contains the provided record
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
        $response = $this->query('GET', sprintf('v1/records/%d/%d/related/', $databoxId, $recordId));

        if (true !== $response->hasProperty('baskets')) {
            throw new RuntimeException('Missing "baskets" property in response content');
        }

        return new ArrayCollection(BasketEntity::fromList($response->getProperty('baskets')));
    }

    /**
     * Find all baskets
     *
     * @return ArrayCollection
     * @throws RuntimeException
     * @throws UnauthorizedException
     * @throws TokenExpiredException
     * @throws NotFoundException
     */
    public function findAll(): ArrayCollection
    {
        $response = $this->query('GET', 'v1/baskets/list/');

        if (true !== $response->hasProperty('baskets')) {
            throw new RuntimeException('Missing "baskets" property in response content');
        }

        return new ArrayCollection(BasketEntity::fromList($response->getProperty('baskets')));
    }
}
