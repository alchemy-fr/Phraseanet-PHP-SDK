<?php

namespace PhraseanetSDK\Entity;

use PhraseanetSDK\EntityManager;

abstract class AbstractEntity
{
    /**
     *
     * @var EntityManager
     */
    protected $em;

    final public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
}
