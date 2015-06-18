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

class Result
{
    /**
     * @ApiField(bind_to="records", type="relation")
     * @ApiRelation(type="one_to_many", target_entity="Record")
     */
    protected $records;
    /**
     * @ApiField(bind_to="stories", type="relation")
     * @ApiRelation(type="one_to_many", target_entity="Story")
     */
    protected $stories;

    /**
     * @return ArrayCollection
     */
    public function getRecords()
    {
        return $this->records;
    }

    public function setRecords(ArrayCollection $records)
    {
        $this->records = $records;
    }

    /**
     * @return ArrayCollection
     */
    public function getStories()
    {
        return $this->stories;
    }

    public function setStories(ArrayCollection $stories)
    {
        $this->stories = $stories;
    }
}
