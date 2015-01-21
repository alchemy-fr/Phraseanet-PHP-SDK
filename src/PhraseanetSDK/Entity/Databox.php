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
use JMS\Serializer\Annotation\ExclusionPolicy as ExclusionPolicy;
use JMS\Serializer\Annotation\VirtualProperty as VirtualProperty;
use JMS\Serializer\Annotation\SerializedName as SerializedName;
use JMS\Serializer\Annotation\Type as Type;

/**
 * @ExclusionPolicy("all")
 */
class Databox
{
    /**
     * @Expose
     * @Id
     * @Type("integer")
     * @ApiField(bind_to="databox_id", type="int")
     */
    protected $id;
    /**
     * @Expose
     * @Type("string")
     * @ApiField(bind_to="name", type="string")
     */
    protected $name;
    /**
     * @Expose
     * @Type("string")
     * @ApiField(bind_to="version", type="string")
     */
    protected $version;
    /**
     * @Expose
     * @Type("array<string, string>")
     * @ApiField(bind_to="labels", type="array")
     */
    protected $labels;

    /**
     * the databox id
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
     * The databox name
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
     * The databox version
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    public function setVersion($version)
    {
        $this->version = $version;
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
