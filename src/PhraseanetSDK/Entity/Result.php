<?php

namespace PhraseanetSDK\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class Result extends AbstractEntity implements EntityInterface
{
    protected $records;
    protected $stories;

    public function getRecords()
    {
        return $this->records;
    }

    public function setRecords(ArrayCollection $records)
    {
        $this->records = $records;
    }

    public function getStories()
    {
        return $this->stories;
    }

    public function setStories(ArrayCollection $stories)
    {
        $this->stories = $stories;
    }

}
