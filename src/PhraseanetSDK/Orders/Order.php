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
 * Class Order
 * @package PhraseanetSDK\Orders
 */
class Order
{

    /**
     * @param array $values
     * @return array
     */
    public static function fromList(array $values)
    {
        $orders = array();

        foreach ($values as $key => $value) {
            $orders[$key] = self::fromValue($value);
        }

        return $orders;
    }

    /**
     * @param \stdClass $value
     * @return Order
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
     * @var \DateTimeInterface|null
     */
    private $deadline;

    /**
     * @var OrderElement[]|null
     */
    private $elements;

    /**
     * @param \stdClass $value
     */
    public function __construct(\stdClass $value)
    {
        $this->source = $value;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->source->id;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->source->status;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getCreated()
    {
        return $this->created ?: $this->created = new \DateTime($this->source->created);
    }

    /**
     * @return string
     */
    public function getUsage()
    {
        return $this->source->usage;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getDeadline()
    {
        return $this->deadline ?: $this->deadline = new \DateTime($this->source->deadline);
    }

    /**
     * @return OrderElement[]
     */
    public function getElements()
    {
        return $this->elements ?: $this->elements = OrderElement::fromList($this->source->elements->data);
    }

    /**
     * @return string|null
     */
    public function getArchiveUrl()
    {
        return $this->source->archive_url;
    }
}
