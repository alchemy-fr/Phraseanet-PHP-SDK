<?php

namespace PhraseanetSDK\Tools\Entity;

use PhraseanetSDK\Exception;
use PhraseanetSDK\Tools\Entity\Manager;
use PhraseanetSDK\Entity\Entity;
use Doctrine\Common\Collections\ArrayCollection;

class Hydrator
{

    /**
     * Transform a string to CamelStyle pr pascalCase
     *
     * @param string $string the string to transform
     * @return string
     */
    protected static function camelize($string)
    {
        $string = str_replace(' ', '', ucwords(str_replace(array('-', '_'), ' ', $string)));

        return lcfirst($string);
    }

    /**
     * Hydrate an entity object from  a source
     *
     * @param type $entity is the entity we want to populate
     * @param \stdClass $object is the source of datas
     * @return \PhraseanetSDK\Tools\Entity\*
     */
    public static function hydrate(Entity $entity, \stdClass $object, Manager $manager)
    {
        foreach (get_object_vars($object) as $propertyName => $propertyValue) {
            $methodName = self::camelize(sprintf('set%s', ucfirst($propertyName)));

            if (method_exists(get_class($entity), $methodName)) {
                if (is_scalar($propertyValue)) {
                    $entity->$methodName($propertyValue);
                } elseif (is_array($propertyValue)) {
                    $entityCollection = new ArrayCollection();

                    foreach ($propertyValue as $object) {
                        if (is_object($object)) {
                            $subEntity = self::hydrate(
                                    $manager->getEntity($propertyName)
                                    , $object
                                    , $manager
                            );

                            $entityCollection->add($subEntity);
                        }
                    }

                    $entity->$methodName($entityCollection);
                } elseif (is_object($propertyValue)) {
                    $subEntity = self::hydrate(
                            $manager->getEntity($propertyName)
                            , $propertyValue
                            , $manager
                    );

                    $entity->$methodName($subEntity);
                }
            }
        }

        return $entity;
    }
}

