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
use PhraseanetSDK\EntityHydrator;

class BasketElement extends AbstractRepository
{
    /**
     * Find all basket elements in the provided basket id
     *
     * @param  integer          $basketId The provided basket id
     * @return ArrayCollection
     * @throws RuntimeException
     */
    public function findByBasket($basketId)
    {
        $response = $this->query('GET', sprintf('baskets/%d/content/', $basketId));

        if (true !== $response->hasProperty('basket_elements')) {
            throw new RuntimeException('Missing "basket_elements" property in response content');
        }

        $basketElements = new ArrayCollection();

        foreach ($response->getProperty('basket_elements') as $basketElementData) {
            $basketElements->add(EntityHydrator::hydrate('basketElement', $basketElementData, $this->em));
        }

        return $basketElements;
    }
}
