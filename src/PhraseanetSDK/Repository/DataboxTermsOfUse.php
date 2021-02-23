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
use PhraseanetSDK\Entity\DataboxTermsOfUse as DataboxTermsOfUseEntity;
use PhraseanetSDK\Exception\NotFoundException;
use PhraseanetSDK\Exception\RuntimeException;
use PhraseanetSDK\Exception\TokenExpiredException;
use PhraseanetSDK\Exception\UnauthorizedException;

class DataboxTermsOfUse extends AbstractRepository
{

    /**
     * Find All the cgus for the choosen databox
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
        $response = $this->query('GET', sprintf('v1/databoxes/%d/termsOfUse/', $databoxId));

        if (true !== $response->hasProperty('termsOfUse')) {
            throw new RuntimeException('Missing "termsOfuse" property in response content');
        }

        return new ArrayCollection(DataboxTermsOfUseEntity::fromList(
            $response->getProperty('termsOfUse')
        ));
    }
}
