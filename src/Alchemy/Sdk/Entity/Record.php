<?php

namespace Alchemy\Sdk\Entity;

class Record
{

  protected $recordId;
  protected $databoxId;
  protected $title;
  protected $mimeType;
  protected $originalName;
  protected $lastModification;
  protected $createdOn;
  protected $collectionId;
  protected $sha256;
  protected $thumbnail;
  protected $technicalInformations;
  protected $phraseaType;
  protected $uuid;
  protected $metadatas;
  protected $subdefs;

  public function getRecordId()
  {
    return $this->recordId;
  }

  public function setRecordId($recordId)
  {
    $this->recordId = $recordId;
  }

  public function getDataboxId()
  {
    return $this->databoxId;
  }

  public function setDataboxId($databoxId)
  {
    $this->databoxId = $databoxId;
  }

  public function getTitle()
  {
    return $this->title;
  }

  public function setTitle($title)
  {
    $this->title = $title;
  }

  public function getMimeType()
  {
    return $this->mimeType;
  }

  public function setMimeType($mimeType)
  {
    $this->mimeType = $mimeType;
  }

  public function getOriginalName()
  {
    return $this->originalName;
  }

  public function setOriginalName($originalName)
  {
    $this->originalName = $originalName;
  }

  public function getLastModification()
  {
    return $this->lastModification;
  }

  public function setLastModification($lastModification)
  {
    $this->lastModification = $lastModification;
  }

  public function getCreatedOn()
  {
    return $this->createdOn;
  }

  public function setCreatedOn($createdOn)
  {
    $this->createdOn = $createdOn;
  }

  public function getCollectionId()
  {
    return $this->collectionId;
  }

  public function setCollectionId($collectionId)
  {
    $this->collectionId = $collectionId;
  }

  public function getSha256()
  {
    return $this->sha256;
  }

  public function setSha256($sha256)
  {
    $this->sha256 = $sha256;
  }

  public function getThumbnail()
  {
    return $this->thumbnail;
  }

  public function setThumbnail($thumbnail)
  {
    $this->thumbnail = $thumbnail;
  }

  public function getMetadatas()
  {
    return $this->metadatas;
  }

  public function setMetadatas($metadatas)
  {
    $this->metadatas = $metadatas;
  }

  public function getPhraseaType()
  {
    return $this->phraseaType;
  }

  public function setPhraseaType($phraseaType)
  {
    $this->phraseaType = $phraseaType;
  }

  public function getUuid()
  {
    return $this->uuid;
  }

  public function setUuid($uuid)
  {
    $this->uuid = $uuid;
  }
  public function getTechnicalInformations()
  {
    return $this->technicalInformations;
  }

  public function setTechnicalInformations($technicalInformations)
  {
    $this->technicalInformations = $technicalInformations;
  }




}