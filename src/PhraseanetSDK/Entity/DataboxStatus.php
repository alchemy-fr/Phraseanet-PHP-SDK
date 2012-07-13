<?php

namespace PhraseanetSDK\Entity;

class DataboxStatus extends AbstractEntity implements EntityInterface
{
    protected $bit;
    protected $labelOn;
    protected $labelOff;
    protected $imgOn;
    protected $imgOff;
    protected $searchable;
    protected $printable;

    /**
     * get the status bit
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
     * get the label status for the OFF status state
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
