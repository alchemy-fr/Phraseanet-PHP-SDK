<?php

/*
 * This file is part of Phraseanet SDK.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhraseanetSDK\Entity;

class DataboxCollection extends AbstractEntity implements EntityInterface
{
    protected $baseId;
    protected $collectionId;
    protected $name;
    protected $recordAmount;

    /**
     * The collection base id
     *
     * @return integer
     */
    public function getBaseId()
    {
        return $this->baseId;
    }

    public function setBaseId($baseId)
    {
        $this->baseId = $baseId;
    }

    /**
     * The collection id
     *
     * @return integer
     */
    public function getCollectionId()
    {
        return $this->collectionId;
    }

    public function setCollectionId($collId)
    {
        $this->collectionId = $collId;
    }

    /**
     * The collection name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * The total record in the collection
     *
     * @return integer
     */
    public function getRecordAmount()
    {
        return $this->record_amount;
    }

    public function setRecordAmount($record_amount)
    {
        $this->record_amount = $record_amount;
    }
}
