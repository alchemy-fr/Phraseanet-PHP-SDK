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
use stdClass;

class DataboxCollection
{
    /**
     * @param stdClass[] $values
     * @return DataboxCollection[]
     */
    public static function fromList(array $values): array
    {
        $collections = array();

        foreach ($values as $value) {
            $collections[$value->base_id] = self::fromValue($value);
        }

        return $collections;
    }

    /**
     * @param stdClass $value
     * @return DataboxCollection
     */
    public static function fromValue(stdClass $value): DataboxCollection
    {
        return new self($value);
    }

    /**
     * @var stdClass
     */
    protected $source;

    /**
     * @var ArrayCollection
     */
    protected $labels;

    /**
     * @param stdClass $source
     */
    public function __construct(stdClass $source)
    {
        $this->source = $source;
    }

    /**
     * @return stdClass
     */
    public function getRawData(): stdClass
    {
        return $this->source;
    }

    /**
     * The collection base id
     *
     * @return integer
     */
    public function getBaseId(): int
    {
        return $this->source->base_id;
    }

    /**
     * The databox id
     *
     * @return integer
     */
    public function getDataboxId(): int
    {
        return $this->source->databox_id;
    }

    /**
     * The collection id
     *
     * @return integer
     */
    public function getCollectionId(): int
    {
        return $this->source->collection_id;
    }

    /**
     * The collection name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->source->name;
    }

    /**
     * The total count of records in the collection
     *
     * @return integer
     */
    public function getRecordAmount(): int
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
