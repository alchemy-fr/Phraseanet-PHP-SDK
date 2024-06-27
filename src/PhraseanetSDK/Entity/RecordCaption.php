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

class RecordCaption
{

    public static function fromList(array $values)
    {
        $captions = array();

        foreach ($values as $name => $value) {
            if (is_object($value)) {
                $captions[$value->meta_structure_id] = self::fromValue($value);
            } else {
                $captions[] = self::fromValue((object) ['name' => $name, 'value' => implode(";", $value)]);
            }
        }

        return $captions;
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
     * Get the related databox meta field id
     *
     * @return integer
     */
    public function getMetaStructureId()
    {
        return isset($this->source->meta_structure_id) ? $this->source->meta_structure_id : 0;
    }

    /**
     * Get the name of the caption
     *
     * @return string
     */
    public function getName()
    {
        return $this->source->name;
    }

    /**
     * Get the value of the caption
     *
     * @return string
     */
    public function getValue()
    {
        return $this->source->value;
    }
}
