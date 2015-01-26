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
use JMS\Serializer\Annotation\Expose as Expose;
use JMS\Serializer\Annotation\VirtualProperty as VirtualProperty;
use JMS\Serializer\Annotation\SerializedName as SerializedName;
use JMS\Serializer\Annotation\Type as Type;
use JMS\Serializer\Annotation\ExclusionPolicy as ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class Metadata
{
    /**
     * @Expose
     * @Id
     * @Type("integer")
     * @ApiField(bind_to="meta_id", type="int")
     */
    protected $id;
    /**
     * @Expose
     * @Type("integer")
     * @ApiField(bind_to="meta_structure_id", type="int")
     */
    protected $metaStructureId;
    /**
     * @Expose
     * @Type("string")
     * @ApiField(bind_to="name", type="string")
     */
    protected $name;
    /**
     * @Expose
     * @Type("string")
     * @ApiField(bind_to="value", type="string")
     */
    protected $value;
    /**
     * @Expose
     * @Type("array<string, string>")
     * @ApiField(bind_to="labels", type="array")
     */
    protected $labels;

    /**
     * Get the metadata id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get the related databox meta field id
     *
     * @return integer
     */
    public function getMetaStructureId()
    {
        return $this->metaStructureId;
    }

    public function setMetaStructureId($structureId)
    {
        $this->metaStructureId = $structureId;
    }

    /**
     * Get the meta name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get the meta value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getLabels()
    {
        return $this->labels;
    }

    /**
     * @param mixed $labels
     */
    public function setLabels($labels)
    {
        $this->labels = $labels;
    }
}
