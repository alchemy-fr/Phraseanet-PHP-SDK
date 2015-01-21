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

use Doctrine\Common\Collections\ArrayCollection;
use PhraseanetSDK\Annotation\ApiField as ApiField;
use PhraseanetSDK\Annotation\ApiRelation as ApiRelation;
use JMS\Serializer\Annotation\Expose as Expose;
use JMS\Serializer\Annotation\VirtualProperty as VirtualProperty;
use JMS\Serializer\Annotation\SerializedName as SerializedName;
use JMS\Serializer\Annotation\Type as Type;
use JMS\Serializer\Annotation\ExclusionPolicy as ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class Result
{
    /**
     * @Expose
     * @Type("ArrayCollection<PhraseanetSDK\Entity\Record>")
     * @ApiField(bind_to="records", type="relation")
     * @ApiRelation(type="one_to_many", target_entity="Record")
     */
    protected $records;
    /**
     * @Expose
     * @Type("ArrayCollection<PhraseanetSDK\Entity\Story>")
     * @ApiField(bind_to="stories", type="relation")
     * @ApiRelation(type="one_to_many", target_entity="Story")
     */
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
