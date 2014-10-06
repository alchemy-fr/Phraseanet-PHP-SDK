<?php

namespace PhraseanetSDK\Annotation;

/**
 *@Annotation
 */
class ApiField
{
    const INT = "int";
    const STRING = "string";
    const DATE = "date";
    const BOOLEAN = "boolean";
    const COLLECTION = "array";
    const RELATION = "relation";

    public $bind_to;
    public $type;
    public $nullable = false;
    public $virtual = false;
} 