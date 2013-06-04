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

class Basket extends AbstractRepository
{
    /**
     * Find all baskets that contains the provided record
     *
     * @param  integer          $databoxId The record databox id
     * @param  integer          $recordId  The record id
     * @return ArrayCollection
     * @throws RuntimeException
     */
    public function findByRecord($databoxId, $recordId)
    {
        $response = $this->query('GET', sprintf('/records/%d/%d/related/', $databoxId, $recordId));

        if (true !== $response->hasProperty('baskets')) {
            throw new RuntimeException('Missing "baskets" property in response content');
        }

        $baskets = new ArrayCollection();

        foreach ($response->getProperty('baskets') as $basketDatas) {
            $baskets->add($this->em->hydrateEntity($this->em->getEntity('basket'), $basketDatas));
        }

        return $baskets;
    }

    /**
     * Find all baskets
     *
     * @return ArrayCollection
     * @throws RuntimeException
     */
    public function findAll()
    {
        $response = $this->query('GET', '/baskets/list/');

        if (true !== $response->hasProperty('baskets')) {
            throw new RuntimeException('Missing "baskets" property in response content');
        }

        $baskets = new ArrayCollection();

        foreach ($response->getProperty('baskets') as $basketDatas) {
            $baskets->add($this->em->hydrateEntity($this->em->getEntity('basket'), $basketDatas));
        }

        return $baskets;
    }
}
