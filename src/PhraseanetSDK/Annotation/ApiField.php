<?php

/*
 * This file is part of Phraseanet SDK.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhraseanetSDK\Annotation;

/** @Annotation */
class ApiField
{
    const INT = "int";
    const STRING = "string";
    const DATE = "date";
    const BOOLEAN = "boolean";
    const FLOAT = "float";
    const COLLECTION = "array";
    const RELATION = "relation";

    public $bind_to;
    public $type;
    public $virtual = false;
}
