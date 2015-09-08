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

class Metadata
{

    public static function fromList(array $values)
    {
        $metadata = array();

        foreach ($values as $value) {
            $metadata[$value->meta_id] = self::fromValue($value);
        }

        return $metadata;
    }

    public static function fromValue(\stdClass $value)
    {
        return new self($value);
    }

    /**
     * @var \stdClass
     */
    protected $source;

    public function __construct(\stdClass $source)
    {
        $this->source = $source;
    }

    /**
     * Get the metadata id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->source->meta_id;
    }

    /**
     * Get the related databox meta field id
     *
     * @return integer
     */
    public function getMetaStructureId()
    {
        return $this->source->meta_structure_id;
    }

    /**
     * Get the meta name
     *
     * @return string
     */
    public function getName()
    {
        return $this->source->name;
    }

    /**
     * Get the meta value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->source->value;
    }

    /**
     * @return string[]
     */
    public function getLabels()
    {
        return $this->source->labels;
    }
}
