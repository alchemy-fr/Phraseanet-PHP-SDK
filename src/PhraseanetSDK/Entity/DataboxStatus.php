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

class DataboxStatus
{
    /**
     * @param stdClass[] $values
     * @return DataboxStatus[]
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
     * @return DataboxStatus
     */
    public static function fromValue(stdClass $value): DataboxStatus
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
     * Get the status bit
     *
     * @return integer
     */
    public function getBit()
    {
        return $this->source->bit;
    }

    /**
     * Get the label status for the ON status state
     *
     * @return string
     */
    public function getLabelOn(): string
    {
        return $this->source->label_on;
    }

    /**
     * Get the label status for the OFF status state
     *
     * @return string
     */
    public function getLabelOff(): string
    {
        return $this->source->label_off;
    }

    /**
     * Get the image for the ON status state
     *
     * @return string
     */
    public function getImgOn(): string
    {
        return $this->source->img_on;
    }

    /**
     * Get the image for the OFF status state
     *
     * @return string
     */
    public function getImgOff(): string
    {
        return $this->source->img_off;
    }

    /**
     * Tell whether the status is searchable
     *
     * @return Boolean
     */
    public function isSearchable(): bool
    {
        return $this->source->searchable;
    }

    /**
     * Tell whether the status is printable
     *
     * @return Boolean
     */
    public function isPrintable(): bool
    {
        return $this->source->printable;
    }

    /**
     * @return ArrayCollection
     */
    public function getLabels(): ArrayCollection
    {
        return $this->labels ?: $this->labels = new ArrayCollection((array) $this->source->labels);
    }
}
