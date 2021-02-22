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
use PhraseanetSDK\Entity\BasketElement as BasketElementEntity;
use PhraseanetSDK\Exception\NotFoundException;
use PhraseanetSDK\Exception\RuntimeException;
use PhraseanetSDK\Exception\TokenExpiredException;
use PhraseanetSDK\Exception\UnauthorizedException;

class BasketElement extends AbstractRepository
{
    /**
     * Find all basket elements in the provided basket id
     *
     * @param integer $basketId The provided basket id
     * @return ArrayCollection
     * @throws RuntimeException
     * @throws UnauthorizedException
     * @throws TokenExpiredException
     * @throws NotFoundException
     */
    public function findByBasket(int $basketId): ArrayCollection
    {
        $response = $this->query('GET', sprintf('v1/baskets/%d/content/', $basketId));

        if (true !== $response->hasProperty('basket_elements')) {
            throw new RuntimeException('Missing "basket_elements" property in response content');
        }

        return new ArrayCollection(BasketElementEntity::fromList(
            $response->getProperty('basket_elements')
        ));
    }
}
