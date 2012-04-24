<?php

namespace PhraseanetSDK\Entity;

use PhraseanetSDK\Tools\Entity\Manager;

abstract class EntityAbstract
{

    protected $em;

    public final function __construct(Manager $em)
    {
        $this->em = $em;
    }
}