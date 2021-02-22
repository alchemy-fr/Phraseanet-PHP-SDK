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
use PhraseanetSDK\Exception\TokenExpiredException;
use PhraseanetSDK\Exception\UnauthorizedException;
use PhraseanetSDK\Entity\Feed as FeedEntity;

class Feed extends AbstractRepository
{
    /**
     * Find a feed by its identifier
     *
     * @param integer $id The desired id
     * @return FeedEntity
     * @throws RuntimeException
     * @throws UnauthorizedException
     * @throws TokenExpiredException
     * @throws NotFoundException
     */
    public function findById(int $id): FeedEntity
    {
        $response = $this->query('GET', sprintf('v1/feeds/%d/content/', $id), array(
            'offset_start' => 0,
            'per_page'     => 0,
            ));

        if (true !== $response->hasProperty('feed')) {
            throw new RuntimeException('Missing "feed" property in response content');
        }

        return FeedEntity::fromValue($this->em, $response->getProperty('feed'));
    }

    /**
     * Find all feeds
     *
     * @return ArrayCollection
     * @throws RuntimeException
     * @throws UnauthorizedException
     * @throws TokenExpiredException
     * @throws NotFoundException
     */
    public function findAll(): ArrayCollection
    {
        $response = $this->query('GET', 'v1/feeds/list/');

        if (true !== $response->hasProperty('feeds')) {
            throw new RuntimeException('Missing "feeds" property in response content');
        }

        return new ArrayCollection(FeedEntity::fromList(
            $this->em,
            $response->getProperty('feeds')
        ));
    }
}
