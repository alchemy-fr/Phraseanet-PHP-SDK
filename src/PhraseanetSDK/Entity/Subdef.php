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
use PhraseanetSDK\Annotation\Id as Id;
use JMS\Serializer\Annotation\Expose as Expose;
use JMS\Serializer\Annotation\VirtualProperty as VirtualProperty;
use JMS\Serializer\Annotation\SerializedName as SerializedName;
use JMS\Serializer\Annotation\Type as Type;
use JMS\Serializer\Annotation\ExclusionPolicy as ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class Subdef
{
    /**
     * @Expose
     * @Id
     * @Type("string")
     * @ApiField(bind_to="name", type="string")
     */
    protected $name;
    /**
     * @Expose
     * @Type("integer")
     * @ApiField(bind_to="height", type="int")
     */
    protected $height;
    /**
     * @Expose
     * @Type("integer")
     * @ApiField(bind_to="width", type="int")
     */
    protected $width;
    /**
     * @Expose
     * @Type("integer")
     * @ApiField(bind_to="filesize", type="int")
     */
    protected $fileSize;
    /**
     * @Expose
     * @Type("string")
     * @ApiField(bind_to="player_type", type="string")
     */
    protected $playerType;
    /**
     * @Expose
     * @Type("string")
     * @ApiField(bind_to="mime_type", type="string")
     */
    protected $mimeType;

    /**
     * @Expose
     * @Type("PhraseanetSDK\Entity\Permalink")
     * @ApiField(bind_to="permalink", type="relation")
     * @ApiRelation(type="one_to_one", target_entity="Permalink")
     */
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
