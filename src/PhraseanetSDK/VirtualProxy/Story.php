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

class Story
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Return the record sub definition as a collection of PhraseanetSDK\Entity\Subdef objects
     *
     * @return ArrayCollection
     */
    public function getSubdefs(\PhraseanetSDK\Entity\Story $story)
    {
        return $this->em->getRepository('record')->findById($story->getDataboxId(), $story->getStoryId());
    }

    public function getCaption(\PhraseanetSDK\Entity\Story $story)
    {
        return $this->em->getRepository('caption')->findByStory($story->getDataboxId(), $story->getStoryId());
    }
}
