<?php

namespace PhraseanetSDK\Entity;

class RecordStatus extends AbstractEntity implements EntityInterface
{
    protected $bit;
    protected $state;

    /**
     * Get the status bit
     *
     * @return intger
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
     * @return boolean
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
