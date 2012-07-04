<?php

namespace PhraseanetSDK\Entity;

class FeedEntryItem extends AbstractEntity implements EntityInterface
{
    /**
     *
     * @var integer
     */
    protected $itemId;

    /**
     *
     * @var Record
     */
    protected $record;

    /**
     * Get the item id
     *
     * @return integer
     */
    public function getItemId()
    {
        return $this->itemId;
    }

    /**
     *
     * @param integer $id
     */
    public function setItemId($id)
    {
        $this->itemId = $id;
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

    /**
     *
     * @param Record $record
     */
    public function setRecord(Record $record)
    {
        $this->record = $record;
    }
}
