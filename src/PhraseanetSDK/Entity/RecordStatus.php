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

class RecordStatus
{
    /**
     * @param stdClass[] $values
     * @return RecordStatus[]
     */
    public static function fromList(array $values): array
    {
        $statuses = array();

        foreach ($values as $value) {
            $statuses[$value->bit] = self::fromValue($value);
        }

        return $statuses;
    }

    /**
     * @param stdClass $value
     * @return RecordStatus
     */
    public static function fromValue(stdClass $value): RecordStatus
    {
        return new self($value);
    }

    /**
     * @var stdClass
     */
    protected $source;

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
     * @return stdClass
     * @deprecated Use getRawData() instead
     */
    public function getSource(): stdClass
    {
        return $this->source;
    }

    /**
     * Get the status bit
     *
     * @return int
     */
    public function getBit(): int
    {
        return $this->source->bit;
    }

    /**
     * Get the status state
     *
     * @return bool
     */
    public function getState(): bool
    {
        return $this->source->state;
    }
}
