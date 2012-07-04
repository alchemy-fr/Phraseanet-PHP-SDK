<?php

namespace PhraseanetSDK\Tools\Repository;

use PhraseanetSDK\Exception;
use PhraseanetSDK\Tools\Entity\Manager;

class Factory
{

    /**
     * Construct a new entity object
     *
     * @param  string                             $type the type of the repository
     * @param  PhraseanetSDK\Tools\Entity\Manager $em   the entity manager
     * @return \PhraseanetSDK\Tools\Entity\*
     * @throws Exception\InvalidArgumentException when types is unknown
     */
    public static function build($type, Manager $em)
    {
        $namespace = '\\PhraseanetSDK\\Repository';

        $classname = ucfirst($type);
        $objectName = sprintf('%s\\%s', $namespace, $classname);

        if ( ! class_exists($objectName)) {
            throw new Exception\InvalidArgumentException(
                sprintf('Class %s does not exists', $objectName)
            );
        }

        return new $objectName($em);
    }
}
