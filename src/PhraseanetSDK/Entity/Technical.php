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

class Technical
{
    /**
     * @param stdClass[] $values
     * @return Technical[]
     */
    public static function fromList(array $values): array
    {
        $technical = array();

        foreach ($values as $value) {
            $technical[] = self::fromValue($value);
        }

        return $technical;
    }

    /**
     * @param stdClass $value
     * @return Technical
     */
    public static function fromValue(stdClass $value): Technical
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
     * Get the technical information name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->source->name;
    }

    /**
     * Get the technical value
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->source->value;
    }
}
