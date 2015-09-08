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

class DataboxStatus
{
    /**
     * @param \stdClass[] $values
     * @return DataboxStatus[]
     */
    public static function fromList(array $values)
    {
        $statuses = array();

        foreach ($values as $value) {
            $statuses[] = self::fromValue($value);
        }

        return $statuses;
    }

    /**
     * @param \stdClass $value
     * @return DataboxStatus
     */
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
    public function getLabelOn()
    {
        return $this->source->label_on;
    }

    /**
     * Get the label status for the OFF status state
     *
     * @return string
     */
    public function getLabelOff()
    {
        return $this->source->label_off;
    }

    /**
     * Get the image for the ON status state
     *
     * @return string
     */
    public function getImgOn()
    {
        return $this->source->img_on;
    }

    /**
     * Get the image for the OFF status state
     *
     * @return string
     */
    public function getImgOff()
    {
        return $this->source->img_off;
    }

    /**
     * Tell whether the status is searchable
     *
     * @return Boolean
     */
    public function isSearchable()
    {
        return $this->source->searchable;
    }

    /**
     * Tell whether the status is printable
     *
     * @return Boolean
     */
    public function isPrintable()
    {
        return $this->source->printable;
    }

    /**
     * @return mixed
     */
    public function getLabels()
    {
        return $this->source->labels;
    }
}
