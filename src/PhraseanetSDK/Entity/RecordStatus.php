<?php

namespace PhraseanetSDK\Entity;

class RecordStatus extends AbstractEntity implements EntityInterface
{
    protected $bit;
    protected $state;

    /**
     * Get the status bit
     *
     * @return integer
     */
    public function getBit()
    {
        return $this->bit;
    }

    public function setBit($bit)
    {
        $this->bit = $bit;
    }

    /**
     * Get the status state
     *
     * @return Boolean
     */
    public function getState()
    {
        return $this->state;
    }

    public function setState($state)
    {
        $this->state = $state;
    }

}
