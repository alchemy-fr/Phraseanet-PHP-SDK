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

class FeedEntryItem
{

    public static function fromList(array $values)
    {
        $items = array();

        foreach ($values as $value) {
            $items[$value->id] = self::fromValue($value);
        }

        return $items;
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
     * @var Record|null
     */
    protected $record;

    /**
     * @param \stdClass $source
     */
    public function __construct(\stdClass $source)
    {
        $this->source = $source;
    }

    /**
     * Get the item id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->source->id;
    }

    /**
     * Get the associated record object
     *
     * @return Record
     */
    public function getRecord()
    {
        return $this->record ?: $this->record = Record::fromValue($this->source->record);
    }
}
