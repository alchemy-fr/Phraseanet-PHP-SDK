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
     * Get the story caption as collection of PhraseanetSDK\Entity\RecordCaption objects
     *
     * @return ArrayCollection
     */
    public function getCaption(\PhraseanetSDK\Entity\Story $story)
    {
        return $this->em->getRepository('caption')->findByRecord($story->getDataboxId(), $story->getStoryId());
    }

    /**
     * Get the story status as collection of PhraseanetSDK\Entity\RecordStatus objects
     *
     * @return ArrayCollection
     */
    public function getStatus(\PhraseanetSDK\Entity\Story $story)
    {
        return $this->em->getRepository('recordStatus')->findByRecord($story->getDataboxId(), $story->getStoryId());
    }
}
