<?php

namespace PhraseanetSDK\Entity;

class Subdef extends EntityAbstract implements Entity
{
    protected $name;
    protected $height;
    protected $width;
    protected $fileSize;
    protected $playerType;
    protected $mimeType;
    protected $permalink;

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function setHeight($height)
    {
        $this->height = $height;
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function setWidth($width)
    {
        $this->width = $width;
    }

    public function getFileSize()
    {
        return $this->fileSize;
    }

    public function setFileSize($fileSize)
    {
        $this->fileSize = $fileSize;
    }

    public function getPlayerType()
    {
        return $this->playerType;
    }

    public function setPlayerType($playerType)
    {
        $this->playerType = $playerType;
    }

    public function getMimeType()
    {
        return $this->mimeType;
    }

    public function setMimeType($mimetype)
    {
        $this->mimeType = $mimetype;
    }

    public function getPermalink()
    {
        return $this->permalink;
    }

    public function setPermalink($permalink)
    {
        $this->permalink = $permalink;
    }
}
