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
     * @param \stdClass[] $values
     * @return Result[]
     */
    public static function fromList(array $values)
    {
        $results = array();

        foreach ($values as $value) {
            $results[] = self::fromValue($value);
        }

        return $results;
    }

    /**
     * @param \stdClass $value
     * @return Result
     */
    public static function fromValue(\stdClass $value)
    {
        return new self($value);
    }

    /**
     * @var \stdClass
     */
    protected $source;

    /**
     * @var ArrayCollection|Record[]
     */
    protected $records;

    /**
     * @var ArrayCollection|Story[]
     */
    protected $stories;

    /**
     * @param \stdClass $source
     */
    public function __construct(\stdClass $source)
    {
        $this->source = $source;
    }

    /**
     * @return ArrayCollection
     */
    public function getRecords()
    {
        return $this->records ?: $this->records = new ArrayCollection(Record::fromList($this->source->records));
    }

    /**
     * @return ArrayCollection
     */
    public function getStories()
    {
        return $this->stories ?: $this->stories = new ArrayCollection(Story::fromList($this->source->stories));
    }
}
