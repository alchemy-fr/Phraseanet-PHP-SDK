<?php

namespace Alchemy\Sdk\Entity;

class Metadatas
{
  protected $id;
  protected $structureId;
  protected $name;
  protected $value;
  
  public function getId()
  {
    return $this->id;
  }

  public function setId($id)
  {
    $this->id = $id;
  }

  public function getStructureId()
  {
    return $this->structureId;
  }

  public function setStructureId($structureId)
  {
    $this->structureId = $structureId;
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