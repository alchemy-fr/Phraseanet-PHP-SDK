<?php

namespace PhraseanetSDK\Entity;

class Feed
{

  protected $id;
  protected $title;
  protected $icon;
  protected $subTitle;
  protected $totalEntries;
  protected $createdOn;
  protected $updatedOn;
  protected $entries;

  public function getId()
  {
    return $this->id;
  }

  public function setId($id)
  {
    $this->id = $id;
  }

  public function getTitle()
  {
    return $this->title;
  }

  public function setTitle($title)
  {
    $this->title = $title;
  }

  public function getIcon()
  {
    return $this->icon;
  }

  public function setIcon($icon)
  {
    $this->icon = $icon;
  }

  public function getSubTitle()
  {
    return $this->subTitle;
  }

  public function setSubTitle($subTitle)
  {
    $this->subTitle = $subTitle;
  }

    public function getTotalEntries()
  {
    return $this->totalEntries;
  }

  public function setTotalEntries($totalEntries)
  {
    $this->totalEntries = $totalEntries;
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

  public function getUpdatedOn()
  {
    return $this->updatedOn;
  }

  public function setUpdatedOn($updatedOn)
  {
    $this->updatedOn = \DateTime::createFromFormat(
                    \DateTime::ATOM
                    , $updatedOn
                    , new \DateTimeZone(date_default_timezone_get())
    );
  }
  
  public function getEntries()
  {
    return $this->entries;
  }

  public function setEntries($entries)
  {
    $this->entries = $entries;
  }



}

