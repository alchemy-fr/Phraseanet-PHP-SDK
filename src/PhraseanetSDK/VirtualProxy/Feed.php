<?php

/*
 * This file is part of Phraseanet SDK.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhraseanetSDK\VirtualProxy;

use PhraseanetSDK\EntityManager;

class Feed
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Get the feed entries
     * Return a collection of PhraseanetSDK\Entity\FeedEntry object
     *
     * /!\ This method requests the API
     *
     * @param  integer         $offset  The offset
     * @param  integer         $perPage The number of items
     * @return ArrayCollection
     */
    public function getEntries(\PhraseanetSDK\Entity\Feed $feed, $offset, $perPage)
    {
        return $this->em->getRepository('entry')->findByFeed($feed->getId(), $offset, $perPage);
    }
}
