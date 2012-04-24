<?php

namespace PhraseanetSDK\Repository;

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
    
}

