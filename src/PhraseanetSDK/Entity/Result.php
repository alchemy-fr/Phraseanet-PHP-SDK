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
