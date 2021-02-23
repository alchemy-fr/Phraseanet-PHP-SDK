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

class Databox
{
    /**
     * @param stdClass[] $values
     * @return Databox[]
     */
    public static function fromList(array $values): array
    {
        $databoxes = array();

        foreach ($values as $value) {
            $databoxes[$value->databox_id] = self::fromValue($value);
        }

        return $databoxes;
    }

    /**
     * @param stdClass $value
     * @return Databox
     */
    public static function fromValue(stdClass $value): Databox
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
     * the databox id
     *
     * @return integer
     */
    public function getId(): int
    {
        return $this->source->databox_id;
    }

    /**
     * The databox name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->source->name;
    }

    /**
     * The databox version
     *
     * @return string
     */
    public function getVersion(): string
    {
        return $this->source->version;
    }

    /**
     * @return ArrayCollection|string[]
     */
    public function getLabels()
    {
        return $this->labels ?: $this->labels = new ArrayCollection((array) $this->source->labels);
    }
}
