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

class Record
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
    public function getSubdefs(\PhraseanetSDK\Entity\Record $record)
    {
        return $this->em->getRepository('subdef')->findByRecord($record->getDataboxId(), $record->getRecordId());
    }

    /**
     * Return the record metadata as a collection of PhraseanetSDK\Entity\metadata objects
     *
     * @return ArrayCollection
     */
    public function getMetadata(\PhraseanetSDK\Entity\Record $record)
    {
        return $this->em->getRepository('metadata')->findByRecord($record->getDataboxId(), $record->getRecordId());
    }

    /**
     * Get the record caption as collection of PhraseanetSDK\Entity\RecordCaption objects
     *
     * @return ArrayCollection
     */
    public function getCaption(\PhraseanetSDK\Entity\Record $record)
    {
        return $this->em->getRepository('caption')->findByRecord($record->getDataboxId(), $record->getRecordId());
    }

    /**
     * Get the record status as collection of PhraseanetSDK\Entity\RecordStatus objects
     *
     * @return ArrayCollection
     */
    public function getStatus(\PhraseanetSDK\Entity\Record $record)
    {
        return $this->em->getRepository('recordStatus')->findByRecord($record->getDataboxId(), $record->getRecordId());
    }
}
