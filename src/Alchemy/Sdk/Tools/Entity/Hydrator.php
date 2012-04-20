<?php

namespace Alchemy\Sdk\Tools\Entity;

use Alchemy\Sdk\Exception;
use Doctrine\Common\Collections\ArrayCollection;

class Hydrator
{

    /**
     * Transform a string to CamelStyle pr pascalCase
     * 
     * @param string $string the string to transform
     * @param boolean $pascalCase enbale pascalCase mode
     * @return string 
     */
    protected static function camelize($string, $pascalCase = false)
    {
        $string = str_replace(' ', '', ucwords(str_replace(array('-', '_'), ' ', $string)));

        if ( ! $pascalCase)
        {
            return lcfirst($string);
        }

        return $string;
    }

    /**
     * Hydrate an entity object from  a source
     * 
     * @param type $entity is the entity we want to populate
     * @param \stdClass $object is the source of datas
     * @return \Alchemy\Sdk\Tools\Entity\*
     */
    public static function hydrate($entity, \stdClass $object)
    {
        foreach (get_object_vars($object) as $propertyName => $propertyValue)
        {
            $methodName = self::camelize(sprintf('set%s', ucfirst($propertyName)));

            if (method_exists(get_class($entity), $methodName))
            {
                if (is_scalar($propertyValue))
                {
                    $entity->$methodName($propertyValue);
                }
                elseif (is_array($propertyValue))
                {
                    $entityCollection = new ArrayCollection();

                    foreach ($propertyValue as $object)
                    {
                        if (is_object($object))
                        {
                            $subEntity = self::hydrate(
                                            Factory::factory($propertyName)
                                            , $object
                            );

                            $entityCollection->add($subEntity);
                        }
                    }

                    $entity->$methodName($entityCollection);
                }
                elseif (is_object($propertyValue))
                {
                    $subEntity = self::hydrate(
                                    Factory::factory($propertyName)
                                    , $propertyValue
                    );

                    $entity->$methodName($subEntity);
                }
            }
        }

        return $entity;
    }

}

