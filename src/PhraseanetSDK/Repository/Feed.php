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

class Feed extends AbstractRepository
{
    /**
     * Find a feed by its identifier
     *
     * @param  integer                    $id The desired id
     * @return \PhraseanetSDK\Entity\Feed
     * @throws RuntimeException
     */
    public function findById($id)
    {
        $response = $this->query('GET', sprintf('feeds/%d/content/', $id), array(
            'offset_start' => 0,
            'per_page'     => 0
            ));

        if (true !== $response->hasProperty('feed')) {
            throw new RuntimeException('Missing "feed" property in response content');
        }

        return EntityHydrator::hydrate('feed', $response->getProperty('feed'), $this->em);
    }

    /**
     * Find all feeds
     *
     * @return ArrayCollection
     * @throws RuntimeException
     */
    public function findAll()
    {
        $response = $this->query('GET', 'feeds/list/');

        if (true !== $response->hasProperty('feeds')) {
            throw new RuntimeException('Missing "feeds" property in response content');
        }

        $feedCollection = new ArrayCollection();

        foreach ($response->getProperty('feeds') as $feedData) {
            $feedCollection->add(EntityHydrator::hydrate('feed', $feedData, $this->em));
        }

        return $feedCollection;
    }
}
