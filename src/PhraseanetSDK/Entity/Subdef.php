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

class Subdef extends AbstractEntity
{
    protected $name;
    protected $height;
    protected $width;
    protected $fileSize;
    protected $playerType;
    protected $mimeType;
    protected $permalink;

    /**
     * Get subdef name
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
     * Get subdef height
     *
     * @return integer
     */
    public function getHeight()
    {
        return $this->height;
    }

    public function setHeight($height)
    {
        $this->height = $height;
    }

    /**
     * Get subdef width
     *
     * @return integer
     */
    public function getWidth()
    {
        return $this->width;
    }

    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * Get subdef file size
     *
     * @return integer
     */
    public function getFileSize()
    {
        return $this->fileSize;
    }

    public function setFileSize($fileSize)
    {
        $this->fileSize = $fileSize;
    }

    /**
     * Get the player type
     *
     * @return string
     */
    public function getPlayerType()
    {
        return $this->playerType;
    }

    public function setPlayerType($playerType)
    {
        $this->playerType = $playerType;
    }

    /**
     * Get subdef mime type
     *
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    public function setMimeType($mimetype)
    {
        $this->mimeType = $mimetype;
    }

    /**
     * Get the permalink related to the subdef
     *
     * @return Permalink
     */
    public function getPermalink()
    {
        return $this->permalink;
    }

    public function setPermalink(Permalink $permalink)
    {
        $this->permalink = $permalink;
    }
}
