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
class DataboxStatus
{
    /**
     * @Expose
     * @Id
     * @Type("integer")
     * @ApiField(bind_to="bit", type="int")
     */
    protected $bit;
    /**
     * @Expose
     * @Type("string")
     * @ApiField(bind_to="label_on", type="string")
     */
    protected $labelOn;
    /**
     * @Expose
     * @Type("string")
     * @ApiField(bind_to="label_off", type="string")
     */
    protected $labelOff;
    /**
     * @Expose
     * @Type("string")
     * @ApiField(bind_to="img_on", type="string")
     */
    protected $imgOn;
    /**
     * @Expose
     * @Type("string")
     * @ApiField(bind_to="img_off", type="string")
     */
    protected $imgOff;
    /**
     * @Expose
     * @Type("boolean")
     * @ApiField(bind_to="searchable", type="boolean")
     */
    protected $searchable;
    /**
     * @Expose
     * @Type("boolean")
     * @ApiField(bind_to="printable", type="boolean")
     */
    protected $printable;
    /**
     * @Expose
     * @Type("array<string, string>")
     * @ApiField(bind_to="labels", type="array")
     */
    protected $labels;

    /**
     * Get the status bit
     *
     * @return integer
     */
    public function getBit()
    {
        return $this->bit;
    }

    public function setBit($bit)
    {
        $this->bit = $bit;
    }

    /**
     * Get the label status for the ON status state
     *
     * @return string
     */
    public function getLabelOn()
    {
        return $this->labelOn;
    }

    public function setLabelOn($labelOn)
    {
        $this->labelOn = $labelOn;
    }

    /**
     * Get the label status for the OFF status state
     *
     * @return string
     */
    public function getLabelOff()
    {
        return $this->labelOff;
    }

    public function setLabelOff($labelOff)
    {
        $this->labelOff = $labelOff;
    }

    /**
     * Get the image for the ON status state
     *
     * @return string
     */
    public function getImgOn()
    {
        return $this->imgOn;
    }

    public function setImgOn($imgOn)
    {
        $this->imgOn = $imgOn;
    }

    /**
     * Get the image for the OFF status state
     *
     * @return string
     */
    public function getImgOff()
    {
        return $this->imgOff;
    }

    public function setImgOff($imgOff)
    {
        $this->imgOff = $imgOff;
    }

    /**
     * Tell whether the status is searchable
     *
     * @return Boolean
     */
    public function isSearchable()
    {
        return $this->searchable;
    }

    public function setSearchable($searchable)
    {
        $this->searchable = $searchable;
    }

    /**
     * Tell whether the status is printable
     *
     * @return Boolean
     */
    public function isPrintable()
    {
        return $this->printable;
    }

    public function setPrintable($printable)
    {
        $this->printable = $printable;
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
