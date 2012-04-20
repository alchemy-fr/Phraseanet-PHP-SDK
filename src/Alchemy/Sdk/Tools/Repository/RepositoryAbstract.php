<?php

namespace Alchemy\Sdk\Tools\Repository;

use Alchemy\Sdk\Tools\Entity\Manager;

abstract class RepositoryAbstract
{

    protected $em;

    public function __construct(Manager $em)
    {
        $this->em = $em;
    }

    protected function getClient()
    {
        return $this->em->getClient();
    }
    
    abstract public function findById($id);
    abstract public function findAll();

}

