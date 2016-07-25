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

class DataboxCollection
{

    public static function fromList(array $values)
    {
        $collections = array();

        foreach ($values as $value) {
            $collections[$value->base_id] = self::fromValue($value);
        }

        return $collections;
    }

    public static function fromValue(\stdClass $value)
    {
        return new self($value);
    }

    /**
     * @var \stdClass
     */
    protected $source;

    /**
     * @var ArrayCollection
     */
    protected $labels;

    /**
     * @param \stdClass $source
     */
    public function __construct(\stdClass $source)
    {
        $this->source = $source;
    }

    /**
     * @return \stdClass
     */
    public function getRawData()
    {
        return $this->source;
    }

    /**
     * The collection base id
     *
     * @return integer
     */
    public function getBaseId()
    {
        return $this->source->base_id;
    }

    /**
     * The databox id
     *
     * @return integer
     */
    public function getDataboxId()
    {
        return $this->source->databox_id;
    }

    /**
     * The collection id
     *
     * @return integer
     */
    public function getCollectionId()
    {
        return $this->source->collection_id;
    }

    /**
     * The collection name
     *
     * @return string
     */
    public function getName()
    {
        return $this->source->name;
    }

    /**
     * The total count of records in the collection
     *
     * @return integer
     */
    public function getRecordAmount()
    {
        return $this->source->record_amount;
    }

    /**
     * @return ArrayCollection|string[]
     */
    public function getLabels()
    {
        return $this->labels ?: $this->labels = new ArrayCollection((array) $this->source->labels);
    }
}
