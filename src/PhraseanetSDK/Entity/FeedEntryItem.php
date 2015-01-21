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

use PhraseanetSDK\Annotation\ApiField as ApiField;
use PhraseanetSDK\Annotation\ApiRelation as ApiRelation;
use PhraseanetSDK\Annotation\Id as Id;

class FeedEntryItem
{
    /**
     * @Id
     * @ApiField(bind_to="item_id", type="int")
     */
    protected $id;
    /**
     * @ApiField(bind_to="record", type="relation")
     * @ApiRelation(type="one_to_one", target_entity="Record")
     */
    protected $record;

    /**
     * Get the item id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get the associated record object
     *
     * @return Record
     */
    public function getRecord()
    {
        return $this->record;
    }

    public function setRecord(Record $record)
    {
        $this->record = $record;
    }
}
