<?php

namespace PhraseanetSDK\Annotation;

/**
 *@Annotation
 */
class ApiRelation
{
    const ONE_TO_ONE = 'one_to_one';
    const ONE_TO_MANY = 'one_to_many';

    public $type;
    public $target_entity;
} 