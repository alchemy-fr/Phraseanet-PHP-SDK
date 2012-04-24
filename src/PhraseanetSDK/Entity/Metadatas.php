<?php

namespace PhraseanetSDK\Entity;

class Metadatas
{
  protected $metaId;
  protected $metaStructureId;
  protected $name;
  protected $value;
  
  public function getMetaId()
  {
    return $this->metaId;
  }

  public function setMetaId($id)
  {
    $this->metaId = $id;
  }

  public function getMetaStructureId()
  {
    return $this->metaStructureId;
  }

  public function setMetaStructureId($structureId)
  {
    $this->metaStructureId = $structureId;
  }

  public function getName()
  {
    return $this->name;
  }

  public function setName($name)
  {
    $this->name = $name;
  }

  public function getValue()
  {
    return $this->value;
  }

  public function setValue($value)
  {
    $this->value = $value;
  }


}