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
use PhraseanetSDK\Entity\FeedEntry;
use PhraseanetSDK\Exception\NotFoundException;
use PhraseanetSDK\Exception\RuntimeException;
use Doctrine\Common\Collections\ArrayCollection;
use PhraseanetSDK\EntityHydrator;
use PhraseanetSDK\Exception\TokenExpiredException;
use PhraseanetSDK\Exception\UnauthorizedException;

class Entry extends AbstractRepository
{
    /**
     * Retrieve the entry identified by its id
     *
     * @param integer $id The entry id
     * @return FeedEntry
     * @throws RuntimeException
     * @throws UnauthorizedException
     * @throws TokenExpiredException
     * @throws NotFoundException
     */
    public function findById(int $id): FeedEntry
    {
        $response = $this->query('GET', sprintf('v1/feeds/entry/%d/', $id));

        if (true !== $response->hasProperty('entry')) {
            throw new RuntimeException('Missing "entry" property in response content');
        }

        return FeedEntry::fromValue($response->getProperty('entry'));
    }

    /**
     * Find all entries that belongs to the feed provided in parameters
     *
     * @param integer $feedId      The feed id
     * @param integer $offsetStart The start offset
     * @param integer $perPage     The number of entries
     * @return ArrayCollection
     * @throws RuntimeException
     * @throws UnauthorizedException
     * @throws TokenExpiredException
     * @throws NotFoundException
     */
    public function findByFeed(int $feedId, int $offsetStart = 0, int $perPage = 5): ArrayCollection
    {
        $response = $this->query('GET', sprintf('v1/feeds/%d/content/', $feedId), array(
            'offset_start' => $offsetStart,
            'per_page' => $perPage,
        ));

        if (true !== $response->hasProperty('entries')) {
            throw new RuntimeException('Missing "entries" property in response content');
        }

        return new ArrayCollection(FeedEntry::fromList($response->getProperty('entries')));
    }
    /**
     * Find entries in the all available rss feed
     *
     * @param  integer $offsetStart The start offset
     * @param  integer $perPage The number of entries
     * @param  array $feeds The feed id's to look for
     * @return ArrayCollection
     * @throws RuntimeException
     * @throws UnauthorizedException
     * @throws TokenExpiredException
     * @throws NotFoundException
     */
    public function findInAggregatedFeed(int $offsetStart = 0, int $perPage = 5, array $feeds = []): ArrayCollection
    {
        $response = $this->query('GET', 'v1/feeds/content/', [
            'offset_start' => $offsetStart,
            'per_page'     => $perPage,
            'feeds'        => $feeds,
        ]);

        if (true !== $response->hasProperty('entries')) {
            throw new RuntimeException('Missing "entries" property in response content');
        }

        return new ArrayCollection(FeedEntry::fromList($response->getProperty('entries')));
    }
}
