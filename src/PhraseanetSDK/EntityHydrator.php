<?php

/*
 * This file is part of Phraseanet SDK.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhraseanetSDK;

use PhraseanetSDK\Annotation\ApiField;
use PhraseanetSDK\Entity\EntityInterface;
use Doctrine\Common\Collections\ArrayCollection;
use PhraseanetSDK\Annotation\ApiRelation;
use PhraseanetSDK\Exception\InvalidArgumentException;
use ProxyManager\Proxy\LazyLoadingInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class EntityHydrator
{
    /**
     * Hydrate an entity object from  a source
     *
     * @param                 $name
     * @param \stdClass       $object
     * @param EntityManager   $em
     *
     * @return \ProxyManager\Proxy\GhostObjectInterface A proxy of the entity
     */
    public static function hydrate($name, \stdClass $object, EntityManager $em)
    {
        $entityClassName = sprintf('\\PhraseanetSDK\\Entity\\%s', ucfirst($name));
        if (!class_exists($entityClassName)) {
            throw new InvalidArgumentException(sprintf('"%s" class doest not exists', $entityClassName));
        }

        $annotationReader = $em->getAnnotationReader();
        $proxyFactory = $em->getProxyFactory();
        $logger = $em->getLogger();

        $reflectionClass = new \ReflectionClass($entityClassName);
        $reflectionProperties = $reflectionClass->getProperties();

        $propertyObjectAnnotation = $annotationReader->getClassAnnotation($reflectionClass, 'PhraseanetSDK\Annotation\ApiObject');

        $extendedObject = (null !== $propertyObjectAnnotation) && (isset($propertyObjectAnnotation->{"extended"})) && !!$propertyObjectAnnotation->{"extended"};

        $dataMapping = array();
        $virtualProperties = array();

        foreach ($reflectionProperties as $reflectionProperty) {
            $propertyName = $reflectionProperty->getName();

            $propertyFieldAnnotation = $annotationReader->getPropertyAnnotation($reflectionProperty, 'PhraseanetSDK\Annotation\ApiField');

            $apiFieldVirtual = isset($propertyFieldAnnotation->{"virtual"}) && !!$propertyFieldAnnotation->{"virtual"};

            if ($apiFieldVirtual) {
                $virtualProperties[strtolower($propertyName)] = $reflectionProperty;
                continue;
            }

            if (!$propertyFieldAnnotation) {
                self::log($logger, LogLevel::DEBUG, sprintf('Missing "@ApiField annotation for "%s->%s" class property', $entityClassName, $propertyName));
                continue;
            }
            if (!isset($propertyFieldAnnotation->{"bind_to"})) {
                self::log($logger, LogLevel::DEBUG, sprintf('Missing "bind_to" property in annotation @ApiField declared for "%s->%s" class property', $entityClassName, $propertyName));
                continue;
            }
            if (!isset($propertyFieldAnnotation->{"type"})) {
                self::log($logger, LogLevel::DEBUG, sprintf('Missing "type" property in annotation @ApiField declared for "%s->%s" class property', $entityClassName, $propertyName));
                continue;
            }

            $apiField = $propertyFieldAnnotation->{"bind_to"};
            $apiFieldType = $propertyFieldAnnotation->{"type"};

            if (!isset($object->{$apiField}) && !$extendedObject) {
                self::log($logger, LogLevel::DEBUG, sprintf('Could not find bind property "%s" declared for "%s->%s class property in response object only [%s] available', $apiField, $entityClassName, $propertyName, implode(', ', array_keys(get_object_vars($object)))));
                continue;
            }

            if (!isset($object->{$apiField}) && $extendedObject && ApiField::RELATION === $apiFieldType) {
                $virtualProperties[strtolower($propertyName)] = $reflectionProperty;
                continue;
            }

            $dataValue = $object->{$apiField};

            if (ApiField::RELATION !== $apiFieldType) {
                $canBeNull = isset($propertyFieldAnnotation->{"nullable"}) && !!$propertyFieldAnnotation->{"nullable"};

                if (null === $dataValue && $canBeNull) {
                    $dataMapping[strtolower($propertyName)] = $dataValue;
                    continue;
                }

                if (null === $dataValue) {
                    self::log($logger, LogLevel::DEBUG, sprintf('Null value found for bind property "%s" declared for "%s->%s class property but declared as a non null one', $apiField, $entityClassName, $propertyName));
                }

                switch ($apiFieldType) {
                    case ApiField::INT:
                        $dataValue = (int) $dataValue;
                        break;
                    case ApiField::BOOLEAN:
                        $dataValue = (boolean) $dataValue;
                        break;
                    case ApiField::STRING:
                        $dataValue = (string) $dataValue;
                        break;
                    case ApiField::DATE:
                        $dataValue = new \DateTime($dataValue);
                        break;
                    case ApiField::COLLECTION:
                        $dataValue = (array) $dataValue;
                        break;
                    default:
                        self::log($logger, LogLevel::DEBUG, sprintf('Unknown type property "%s" in annotation @ApiField declared for "%s->%s" class property', $apiFieldType, $entityClassName, $propertyName));
                }

                $dataMapping[strtolower($propertyName)] = $dataValue;
                continue;
            }

            $propertyRelationAnnotation = $annotationReader->getPropertyAnnotation($reflectionProperty, 'PhraseanetSDK\Annotation\ApiRelation');

            if (!$propertyRelationAnnotation) {
                self::log($logger, LogLevel::DEBUG, sprintf('Missing "@ApiRelationField annotation for "%s->%s" class property', $entityClassName, $propertyName));
                continue;
            }
            if (!isset($propertyRelationAnnotation->{"target_entity"})) {
                self::log($logger, LogLevel::DEBUG, sprintf('Missing "target_entity" property in annotation @ApiRelationField declared for "%s->%s" class property', $entityClassName, $propertyName));
                continue;
            }
            if (!isset($propertyRelationAnnotation->{"type"})) {
                self::log($logger, LogLevel::DEBUG, sprintf('Missing "type" property in annotation @ApiRelationField declared for "%s->%s" class property', $entityClassName, $propertyName));
                continue;
            }

            $apiRelationType = $propertyRelationAnnotation->{"type"};
            $apiRelationTargetEntity = $propertyRelationAnnotation->{"target_entity"};

            $subEntityClassName = sprintf('PhraseanetSDK\Entity\%s', $apiRelationTargetEntity);

            if (!class_exists($subEntityClassName)) {
                throw new InvalidArgumentException(sprintf('Mapping error in annotation @ApiRelation for property "target_entity" declared for %s->%s class property, entity %s not found', $entityClassName, $propertyName, $subEntityClassName));
            }
            if (!in_array($apiRelationType, array(ApiRelation::ONE_TO_ONE, ApiRelation::ONE_TO_MANY))) {
                self::log($logger, LogLevel::DEBUG, sprintf('Unknown type property "%s" in annotation @ApiRelationField declared for "%s->%s" class property', $apiRelationType, $entityClassName, $propertyName));
                continue;
            }
            if ($apiRelationType === ApiRelation::ONE_TO_ONE) {
                $dataMapping[strtolower($propertyName)] = self::hydrate($apiRelationTargetEntity, $dataValue, $em);
                continue;
            }
            if ($apiRelationType === ApiRelation::ONE_TO_MANY) {
                $collectionData = new ArrayCollection();
                foreach ($dataValue as $subValue) {
                    $collectionData->add(self::hydrate($apiRelationTargetEntity, $subValue, $em));
                }
                $dataMapping[strtolower($propertyName)] = $collectionData;
                continue;
            }
        }

        $proxy = $proxyFactory->createProxy($entityClassName, function (LazyLoadingInterface $proxy, $method, array $parameters, & $initializer) use ($name, $dataMapping, $virtualProperties, $em) {
            if (empty($dataMapping) && empty($virtualProperties)) {
                $initializer = null;

                return true;
            }
            if (stripos($method, 'get') !== 0) {

                return true;
            }
            $getter = $method;

            if (null !== call_user_func_array(array($proxy, $getter), $parameters)) {

                return true;
            }
            $propertyName = substr($getter, 3, strlen($getter));
            $setter = sprintf('set%s', $propertyName);
            if (!method_exists($proxy, $setter)) {
                self::log($em->getLogger(), LogLevel::DEBUG, sprintf('Could not find setter %s for property %s in class %s', $setter, $propertyName, get_class($proxy)));

                return true;
            }

            $propertyName = strtolower($propertyName);

            if (!array_key_exists($propertyName, $virtualProperties)) {
                if (!array_key_exists($propertyName, $dataMapping)) {
                    self::log($em->getLogger(), LogLevel::DEBUG, sprintf('Could not find property "%s" in data mapping [%s]', $propertyName, implode(', ', array_keys($dataMapping))));

                    return true;
                }
                call_user_func_array(array($proxy, $setter), array($dataMapping[$propertyName]));
                unset($dataMapping[$propertyName]);

                return true;
            }

            $virtualProxy = $em->getVirtualProxy($name);

            if (false === method_exists($virtualProxy, $method)) {
                self::log($em->getLogger(), LogLevel::DEBUG, sprintf('Method for virtual object %s::%s is not defined', $name, $method));

                return true;
            }

            array_unshift($parameters, $proxy);

            $data = call_user_func_array(array($virtualProxy, $method), $parameters);
            call_user_func_array(array($proxy, $setter), array($data));

            unset($virtualProperties[$propertyName]);

            return true;
        });

        return $proxy;
    }

    private static function log(LoggerInterface $logger, $level, $msg)
    {
        if (!$logger) {
            return;
        }

        $logger->log($level, $msg);
    }
}
