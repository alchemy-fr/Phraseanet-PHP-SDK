<?php

namespace PhraseanetSDK\Tools\Entity;

use PhraseanetSDK\Exception;

class Factory
{

    /**
     * Map keys from API to a specific entity type
     * @var array 
     */
    protected static $mapKeyToObjectType = array(
        'entries' => 'entry',
        'technical_informations' => 'technical',
        'thumbnail' => 'subdef',
        'items' => 'item',
        'record' => 'record',
        'permalink' => 'permalink'
    );

    /**
     * Construct a new entity object
     * 
     * @param string $type the type of the entity
     * @param string $namespace namespace look
     * @return \PhraseanetSDK\Tools\Entity\*
     * @throws Exception\InvalidArgumentException when types is unknown
     */
    public static function factory($type)
    {
        if (isset(self::$mapKeyToObjectType[$type]))
        {
            $type = self::$mapKeyToObjectType[$type];
        }

        $namespace = '\\PhraseanetSDK\\Entity';

        $classname = ucfirst($type);
        $objectName = sprintf('%s\\%s', $namespace, $classname);

        if ( ! class_exists($objectName))
        {
            throw new Exception\InvalidArgumentException(
                    sprintf('Class %s does not exists', $objectName)
            );
        }

        return new $objectName();
    }

}

