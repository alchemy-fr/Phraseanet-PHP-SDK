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
use stdClass;

class RecordCaption
{
    /**
     * @param stdClass[] $values
     * @return RecordCaption[]
     */
    public static function fromList(array $values): array
    {
        $captions = array();

        foreach ($values as $value) {
            $captions[$value->meta_structure_id] = self::fromValue($value);
        }

        return $captions;
    }

    /**
     * @param stdClass $value
     * @return RecordCaption
     */
    public static function fromValue(stdClass $value): RecordCaption
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
     * Get the related databox meta field id
     *
     * @return integer
     */
    public function getMetaStructureId(): int
    {
        return $this->source->meta_structure_id;
    }

    /**
     * Get the name of the caption
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->source->name;
    }

    /**
     * Get the value of the caption
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->source->value;
    }
}
