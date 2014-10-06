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

class DataboxTermsOfUse extends AbstractRepository
{

    /**
     * Find All the cgus for the choosen databox
     *
     * @param  integer          $databoxId The databox id
     * @return ArrayCollection
     * @throws RuntimeException
     */
    public function findByDatabox($databoxId)
    {
        $response = $this->query('GET', sprintf('databoxes/%d/termsOfUse/', $databoxId));

        if (true !== $response->hasProperty('termsOfUse')) {
            throw new RuntimeException('Missing "termsOfuse" property in response content');
        }

        $metaCollection = new ArrayCollection();

        foreach ($response->getProperty('termsOfUse') as $metadata) {
            $metaCollection->add(EntityHydrator::hydrate('databoxTermsOfUse', $metadata, $this->em));
        }

        return $metaCollection;
    }
}
