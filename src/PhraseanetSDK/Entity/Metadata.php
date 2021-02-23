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

class Metadata
{
    /**
     * @param stdClass[] $values
     * @return Metadata[]
     */
    public static function fromList(array $values): array
    {
        $metadata = array();

        foreach ($values as $value) {
            $metadata[$value->meta_id] = self::fromValue($value);
        }

        return $metadata;
    }

    /**
     * @param stdClass $value
     * @return Metadata
     */
    public static function fromValue(stdClass $value): Metadata
    {
        return new self($value);
    }

    /**
     * @var stdClass
     */
    protected $source;

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
     * Get the metadata id
     *
     * @return integer
     */
    public function getId(): int
    {
        return $this->source->meta_id;
    }

    /**
     * Get the related databox meta field id
     *
     * @return integer
     */
    public function getMetaStructureId(): int
    {
        return $this->source->meta_structure_id;
    }

    /**
     * Get the meta name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->source->name;
    }

    /**
     * Get the meta value
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->source->value;
    }

    /**
     * @return string[]
     */
    public function getLabels(): array
    {
        return $this->source->labels;
    }
}
