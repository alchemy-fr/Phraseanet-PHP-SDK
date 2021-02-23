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

use stdClass;

class FeedEntryItem
{
    /**
     * @param stdClass[] $values
     * @return FeedEntryItem[]
     */
    public static function fromList(array $values): array
    {
        $items = array();

        foreach ($values as $value) {
            $items[$value->item_id] = self::fromValue($value);
        }

        return $items;
    }

    /**
     * @param stdClass $value
     * @return FeedEntryItem
     */
    public static function fromValue(stdClass $value): FeedEntryItem
    {
        return new self($value);
    }

    /**
     * @var stdClass
     */
    protected $source;

    /**
     * @var Record|null
     */
    protected $record;

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
     * Get the item id
     *
     * @return integer
     */
    public function getId(): int
    {
        return $this->source->item_id;
    }

    /**
     * Get the associated record object
     *
     * @return Record
     */
    public function getRecord(): Record
    {
        return $this->record ?: $this->record = Record::fromValue($this->source->record);
    }
}
