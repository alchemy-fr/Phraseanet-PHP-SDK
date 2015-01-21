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
use PhraseanetSDK\Annotation\Id as Id;
use JMS\Serializer\Annotation\Expose as Expose;
use JMS\Serializer\Annotation\VirtualProperty as VirtualProperty;
use JMS\Serializer\Annotation\SerializedName as SerializedName;
use JMS\Serializer\Annotation\Type as Type;
use JMS\Serializer\Annotation\ExclusionPolicy as ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class RecordStatus
{
    /**
     * @Expose
     * @Id
     * @Type("integer")
     * @ApiField(bind_to="bit", type="int")
     */
    protected $bit;
    /**
     * @Expose
     * @Type("boolean")
     * @ApiField(bind_to="state", type="boolean")
     */
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
