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

class RecordStatus extends AbstractEntity
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
