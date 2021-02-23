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
use PhraseanetSDK\Exception\NotFoundException;
use PhraseanetSDK\Exception\RuntimeException;
use Doctrine\Common\Collections\ArrayCollection;
use PhraseanetSDK\EntityHydrator;
use PhraseanetSDK\Exception\TokenExpiredException;
use PhraseanetSDK\Exception\UnauthorizedException;
use PhraseanetSDK\Entity\Quarantine as QuarantineEntity;

class Quarantine extends AbstractRepository
{
    /**
     * Find a list of quarantine items stating at $offsetStart with
     * $perPage items number
     *
     * @param  integer          $offsetStart
     * @param  integer          $perPage
     * @return ArrayCollection
     * @throws RuntimeException
     * @throws UnauthorizedException
     * @throws TokenExpiredException
     * @throws NotFoundException
     */
    public function findByOffset(int $offsetStart = 0, int $perPage = 10): ArrayCollection
    {
        $response = $this->query('GET', 'v1/quarantine/list/', [
            'offset_start' => $offsetStart,
            'per_page'     => $perPage,
            ]);

        if (true !== $response->hasProperty('quarantine_items')) {
            throw new RuntimeException('Missing "quarantine_items" property in response content');
        }

        return new ArrayCollection(QuarantineEntity::fromList(
            $response->getProperty('quarantine_items')
        ));
    }

    /**
     * Find a quarantine item by its identifier
     *
     * @param integer $id The desired id
     * @return QuarantineEntity
     * @throws RuntimeException
     * @throws UnauthorizedException
     * @throws TokenExpiredException
     * @throws NotFoundException
     */
    public function findById(int $id): QuarantineEntity
    {
        $response = $this->query('GET', sprintf('v1/quarantine/item/%d/', $id));

        if (true !== $response->hasProperty('quarantine_item')) {
            throw new RuntimeException('Missing "quarantine_item" property in response content');
        }

        return QuarantineEntity::fromValue($response->getProperty('quarantine_item'));
    }
}
