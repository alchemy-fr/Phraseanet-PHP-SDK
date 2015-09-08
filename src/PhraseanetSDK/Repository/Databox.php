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

class Databox extends AbstractRepository
{
    /**
     * Find All databoxes
     *
     * @return ArrayCollection
     * @throws RuntimeException
     */
    public function findAll()
    {
        $response = $this->query('GET', 'databoxes/list/');

        if (true !== $response->hasProperty('databoxes')) {
            throw new RuntimeException('Missing "databoxes" property in response content');
        }

        return new ArrayCollection(\PhraseanetSDK\Entity\Databox::fromList($response->getProperty('databoxes')));
    }
}
