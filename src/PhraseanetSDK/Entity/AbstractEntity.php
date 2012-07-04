<?php

namespace PhraseanetSDK\Entity;

use PhraseanetSDK\Tools\Entity\Manager;

abstract class AbstractEntity
{
    /**
     *
     * @var PhraseanetSDK\Tools\Entity\Manager
     */
    protected $em;

    final public function __construct(Manager $em)
    {
        $this->em = $em;
    }
}
