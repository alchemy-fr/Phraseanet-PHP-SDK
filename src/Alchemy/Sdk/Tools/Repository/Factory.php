<?php

namespace Alchemy\Sdk\Tools\Repository;

use Alchemy\Sdk\Exception;
use Alchemy\Sdk\Tools\Entity\Manager;

class Factory
{
    /**
     * Construct a new entity object
     * 
     * @param string $type the type of the repository
     * @param Alchemy\Sdk\Tools\Entity\Manager $em the entity manager
     * @return \Alchemy\Sdk\Tools\Entity\*
     * @throws Exception\InvalidArgumentException when types is unknown
     */
    public static function factory($type, Manager $em)
    {
        $namespace = '\\Alchemy\\Sdk\\Repository';

        $classname = ucfirst($type);
        $objectName = sprintf('%s\\%s', $namespace, $classname);

        if ( ! class_exists($objectName))
        {
            throw new Exception\InvalidArgumentException(
                    sprintf('Class %s does not exists', $objectName)
            );
        }

        return new $objectName($em);
    }

}

