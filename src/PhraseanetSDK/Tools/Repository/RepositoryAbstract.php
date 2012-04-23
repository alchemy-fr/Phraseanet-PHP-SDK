<?php

namespace PhraseanetSDK\Tools\Repository;

use PhraseanetSDK\Tools\Entity\Manager;

abstract class RepositoryAbstract
{

    protected $em;

    public function __construct(Manager $em)
    {
        $this->em = $em;
    }

    /**
     * @codeCoverageIgnore
     */
    protected function getClient()
    {
        return $this->em->getClient();
    }
    
    abstract public function findById($id);
    abstract public function findAll();

}

