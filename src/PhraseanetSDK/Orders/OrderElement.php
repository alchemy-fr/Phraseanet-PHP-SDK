<?php

/*
 * This file is part of phraseanet/php-sdk.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhraseanetSDK\Orders;

/**
 * Class OrderElement
 * @package PhraseanetSDK\Orders
 */
class OrderElement 
{

    const STATUS_ACCEPTED = 'accepted';

    const STATUS_REJECTED = 'rejected';

    /***
     * @param \stdClass[] $values
     * @return OrderElement[]
     */
    public static function fromList(array $values)
    {
        $elements = array();

        foreach ($values as $key => $value) {
            $elements[$key] = self::fromValue($value);
        }

        return $elements;
    }

    /**
     * @param \stdClass $value
     * @return OrderElement
     */
    public static function fromValue(\stdClass $value)
    {
        return new self($value);
    }

    /**
     * @var \stdClass
     */
    private $source;

    /**
     * @var \DateTimeInterface|null
     */
    private $created;

    /**
     * @param \stdClass $source
     */
    public function __construct(\stdClass $source)
    {
        $this->source = $source;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return (int) $this->source->id;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created ?: $this->created = new \DateTime($this->source->created);
    }

    /**
     * @return int
     */
    public function getDataboxId()
    {
        return (int) $this->source->record->databox_id;
    }

    /**
     * @return int
     */
    public function getRecordId()
    {
        return (int) $this->source->record->record_id;
    }

    public function getStatus()
    {
        return $this->source->status;
    }

    /**
     * @return int
     */
    public function getIndex()
    {
        return (int) $this->source->index;
    }
}
