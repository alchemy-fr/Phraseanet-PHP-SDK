<?php

namespace PhraseanetSDK\Entity;

class Item extends EntityAbstract implements Entity
{
    protected $itemId;
    protected $record;

    public function getItemId()
    {
        return $this->itemId;
    }

    public function setItemId($id)
    {
        $this->itemId = $id;
    }

    public function getRecord()
    {
        return $this->record;
    }

    public function setRecord($record)
    {
        $this->record = $record;
    }
}
