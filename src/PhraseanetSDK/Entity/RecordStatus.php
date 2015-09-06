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

use PhraseanetSDK\Annotation\ApiField as ApiField;
use PhraseanetSDK\Annotation\Id as Id;

class RecordStatus
{

    public static function fromList(array $values)
    {
        $statuses = array();

        foreach ($values as $value) {
            $statuses[] = self::fromValue($value);
        }

        return $statuses;
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
     * @param \stdClass $source
     */
    public function __construct(\stdClass $source)
    {
        $this->source = $source;
    }

    /***
     * @return \stdClass
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Get the status bit
     *
     * @return int
     */
    public function getBit()
    {
        return $this->source->bit;
    }

    /**
     * Get the status state
     *
     * @return bool
     */
    public function getState()
    {
        return $this->source->state;
    }
}
