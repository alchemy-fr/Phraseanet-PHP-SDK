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

class Databox
{

    public static function fromList(array $values)
    {
        $databoxes = array();

        foreach ($values as $value) {
            $databoxes[$value->databox_id] = self::fromValue($value);
        }

        return $databoxes;
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
     * @var ArrayCollection
     */
    protected $labels;

    /**
     * @param \stdClass $source
     */
    public function __construct(\stdClass $source)
    {
        $this->source = $source;
    }

    /**
     * the databox id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->source->databox_id;
    }

    /**
     * The databox name
     *
     * @return string
     */
    public function getName()
    {
        return $this->source->name;
    }

    /**
     * The databox version
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->source->version;
    }

    /**
     * @return string[]
     */
    public function getLabels()
    {
        return $this->labels ?: $this->labels = new ArrayCollection((array) $this->source->labels);
    }
}
