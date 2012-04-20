<?php

namespace PhraseanetSDK\Entity;

class Entry
{
  protected $id;
  protected $authorEmail;
  protected $authorName;
  protected $title;
  protected $subtitle;
  protected $createdOn;
  protected $updatedOn;
  protected $items;
  
  public function getId()
  {
    return $this->id;
  }

  public function setId($id)
  {
    $this->id = $id;
  }

  public function getAuthorEmail()
  {
    return $this->authorEmail;
  }

  public function setAuthorEmail($authorEmail)
  {
    $this->authorEmail = $authorEmail;
  }

  public function getAuthorName()
  {
    return $this->authorName;
  }

  public function setAuthorName($authorName)
  {
    $this->authorName = $authorName;
  }

  public function getTitle()
  {
    return $this->title;
  }

  public function setTitle($title)
  {
    $this->title = $title;
  }

  public function getSubtitle()
  {
    return $this->subtitle;
  }

  public function setSubtitle($subtitle)
  {
    $this->subtitle = $subtitle;
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

  public function getItems()
  {
    return $this->items;
  }

  public function setItems($items)
  {
    $this->items = $items;
  }


}