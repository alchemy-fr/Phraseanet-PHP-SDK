<?php

namespace PhraseanetSDK\Entity;

use PhraseanetSDK\EntityManager;
use PhraseanetSDK\Entity\EntityInterface;
use PhraseanetSDK\Entity\EntityBagInterface;
use Doctrine\Common\Collections\ArrayCollection;

class EntityHydrator
{
    /**
     * @see http://www.php.net/manual/en/language.variables.basics.php
     */
    CONST OBJECT_SCHEMA_ENDPOINT = 'http://api.phraseanet.com/objects/';
    CONST TYPE_JSON_SCHEMA = '@entity';
    /**
     * Transform a string to CamelStyle pr pascalCase
     *
     * @param  string $string the string to transform
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
     * @param type          $entity is the entity we want to populate
     * @param \stdClass     $object is the source of datas
     * @param EntityManager $em     The entity manager
     *
     * @return \PhraseanetSDK\Entity\EntityInterface
     */
    public static function hydrate(EntityInterface $entity, \stdClass $object, EntityManager $em)
    {
        $className = get_class($entity);
        $reflectionClass = new \ReflectionClass($className);
        
        foreach (get_object_vars($object) as $propertyName => $propertyValue) {
            
            if(preg_match('/^@(.*)+/', $propertyName)) {
                continue;
            }
            
            $methodName = self::camelize(sprintf('set%s', ucfirst($propertyName)));
            if ($reflectionClass->hasMethod($methodName)) {
                $reflectionMethod = new \ReflectionMethod($className, $methodName);

                if ($reflectionMethod->getNumberOfParameters() > 0) {
                    $parameters = $reflectionMethod->getParameters();

                    if (is_scalar($propertyValue)) {
                        foreach ($parameters as $parameter) {
                            /* @var $parameter \ReflectionParameter */
                            if (null === $parameter->getClass()) {
                                $entity->$methodName($propertyValue);
                            } elseif ('DateTime' === $parameter->getClass()->getName()) {
                                $date = \DateTime::createFromFormat(
                                        \DateTime::ATOM
                                        , $propertyValue
                                        , new \DateTimeZone(date_default_timezone_get())
                                );

                                $entity->$methodName($date);
                            }
                        }
                    } elseif (is_array($propertyValue)) {
                        $entityCollection = new ArrayCollection();

                        foreach ($propertyValue as $object) {
                            if (is_object($object)) {
                                 // hack
                                $subObjectType = self::extractObjectType($object);

                                if($propertyName == 'results') {
                                    $subObjectType = 'records';
                                }
                                
                                $subEntity = self::hydrate(
                                        $em->getEntity(null === $subObjectType ? $propertyName : $subObjectType)
                                        , $object
                                        , $em
                                );
                                
                                $entityCollection->add($subEntity);
                            } elseif (is_scalar($object)) {
                                $entityCollection->add($object);
                            }
                        }

                        foreach ($parameters as $parameter) {
                            /* @var $parameter \ReflectionParameter */
                            if($parameter->getClass() && $propertyName == 'results') {
                                $result = new Result($em);
                                $result->setRecords($entityCollection);
                                $entity->$methodName($result);
                            } else if ($parameter->getClass() && $parameter->getClass()->isInstance($entityCollection)) {
                                $entity->$methodName($entityCollection);
                            }
                        }
                    } elseif (is_object($propertyValue)) {
                        if ( ! ctype_digit($propertyName)) {
                            
                            $subObjectType = self::extractObjectType($propertyValue);
                            
                            if(($hydrateEntity = $em->getEntity(null === $subObjectType ? $propertyName : $subObjectType)) instanceof StoryMetadataBag) {
                                $subEntity = new ArrayCollection(get_object_vars($propertyValue));
                            } else {
                                $subEntity = self::hydrate(
                                    $hydrateEntity
                                    , $propertyValue
                                    , $em
                                );
                            }

                            foreach ($parameters as $parameter) {
                                /* @var $parameter \ReflectionParameter */
                                if ($parameter->getClass() && $parameter->getClass()->isInstance($subEntity)) {
                                    $entity->$methodName($subEntity);
                                }
                            }
                        }
                    }
                }
            }
        }

        return $entity;
    }
    
    private static function extractObjectType(\stdClass $object)
    {
        if(property_exists($object, self::TYPE_JSON_SCHEMA)) {
            $type = substr($object->{self::TYPE_JSON_SCHEMA}, - (strlen($object->{self::TYPE_JSON_SCHEMA}) - strlen(self::OBJECT_SCHEMA_ENDPOINT)));
            return $type;
        }
        
        return null;
    }
}
