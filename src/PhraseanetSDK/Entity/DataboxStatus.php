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
use PhraseanetSDK\Annotation\ApiRelation as ApiRelation;

class DataboxStatus
{
    /**
     * @ApiField(bind_to="bit", type="int")
     */
    protected $bit;
    /**
     * @ApiField(bind_to="label_on", type="string")
     */
    protected $labelOn;
    /**
     * @ApiField(bind_to="label_off", type="string")
     */
    protected $labelOff;
    /**
     * @ApiField(bind_to="img_on", type="string")
     */
    protected $imgOn;
    /**
     * @ApiField(bind_to="img_off", type="string")
     */
    protected $imgOff;
    /**
     * @ApiField(bind_to="searchable", type="boolean")
     */
    protected $searchable;
    /**
     * @ApiField(bind_to="printable", type="boolean")
     */
    protected $printable;

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
}
