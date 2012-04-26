<?php

namespace PhraseanetSDK\Entity;

class Technical extends EntityAbstract implements Entity
{
    protected $bits;
    protected $channels;
    protected $orientation;

    public function getBits()
    {
        return $this->bits;
    }

    public function setBits($bits)
    {
        $this->bits = $bits;
    }

    public function getChannels()
    {
        return $this->channels;
    }

    public function setChannels($channels)
    {
        $this->channels = $channels;
    }

    public function getOrientation()
    {
        return $this->orientation;
    }

    public function setOrientation($orientation)
    {
        $this->orientation = $orientation;
    }
}
