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
use PhraseanetSDK\EntityManager;

class Result
{
    /**
     * @param EntityManager $entityManager
     * @param \stdClass[] $values
     * @return Result[]
     */
    public static function fromList(EntityManager $entityManager, array $values)
    {
        $results = array();

        foreach ($values as $value) {
            $results[] = self::fromValue($entityManager, $value);
        }

        return $results;
    }

    /**
     * @param EntityManager $entityManager
     * @param \stdClass $value
     * @return Result
     */
    public static function fromValue(EntityManager $entityManager, \stdClass $value)
    {
        return new self($entityManager, $value);
    }

    /**
     * @var EntityManager
     */
    protected $entityManager;

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
     * @param EntityManager $entityManager
     * @param \stdClass $source
     */
    public function __construct(EntityManager $entityManager, \stdClass $source)
    {
        $this->entityManager = $entityManager;
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
        return $this->stories ?: $this->stories = new ArrayCollection(Story::fromList(
            $this->entityManager,
            $this->source->stories
        ));
    }
}
