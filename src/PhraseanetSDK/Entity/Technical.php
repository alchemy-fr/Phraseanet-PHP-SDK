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

class Technical
{

    public static function fromList(array $values)
    {
        $technical = array();

        foreach ($values as $name => $value) {
            if (is_object($value)) {
                $techValue = $value;
            } else {
                $techValue = (object) ['name' => $name, 'value' => $value];
            }

            $technical[] = self::fromValue($techValue);
        }

        return $technical;
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

    /**
     * @return \stdClass
     */
    public function getRawData()
    {
        return $this->source;
    }

    /**
     * @return \stdClass
     * @deprecated Use getRawData() instead
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Get the technical information name
     *
     * @return string
     */
    public function getName()
    {
        return $this->source->name;
    }

    /**
     * Get the technical value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->source->value;
    }
}
