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
use PhraseanetSDK\Exception\RuntimeException;
use Doctrine\Common\Collections\ArrayCollection;
use PhraseanetSDK\EntityHydrator;

class DataboxStatus extends AbstractRepository
{
    /**
     * The status of the desired databox
     *
     * @param  integer          $databoxId the databox id
     * @return ArrayCollection
     * @throws RuntimeException
     */
    public function findByDatabox($databoxId)
    {
        $response = $this->query('GET', sprintf('v1/databoxes/%d/status/', $databoxId));

        if (true !== $response->hasProperty('status')) {
            throw new RuntimeException('Missing "status" property in response content');
        }

        return new ArrayCollection(\PhraseanetSDK\Entity\DataboxStatus::fromList(
            $response->getProperty('status')
        ));
    }
}
