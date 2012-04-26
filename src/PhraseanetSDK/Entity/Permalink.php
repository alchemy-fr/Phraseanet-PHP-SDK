<?php

namespace PhraseanetSDK\Entity;

class Permalink extends EntityAbstract implements Entity
{
    protected $id;
    protected $isActivated;
    protected $label;
    protected $lastModified;
    protected $createdOn;
    protected $pageUrl;
    protected $url;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getIsActivated()
    {
        return $this->isActivated;
    }

    public function setIsActivated($isActivated)
    {
        $this->isActivated = $isActivated;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function setLabel($label)
    {
        $this->label = $label;
    }

    public function getLastModified()
    {
        return $this->lastModified;
    }

    public function setLastModified($lastModified)
    {
        $this->lastModified = \DateTime::createFromFormat(
                \DateTime::ATOM
                , $lastModified
                , new \DateTimeZone(date_default_timezone_get())
        );
    }

    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    public function setCreatedOn($createdOn)
    {
        $this->createdOn = \DateTime::createFromFormat(
                \DateTime::ATOM
                , $createdOn
                , new \DateTimeZone(date_default_timezone_get())
        );
    }

    public function getPageUrl()
    {
        return $this->pageUrl;
    }

    public function setPageUrl($pageUrl)
    {
        $this->pageUrl = $pageUrl;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }
}
