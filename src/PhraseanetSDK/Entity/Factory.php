<?php

/*
 * This file is part of Phraseanet SDK.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhraseanetSDK\Entity;

use PhraseanetSDK\Exception;
use PhraseanetSDK\EntityManager;

class Factory
{
    /**
     * Map keys from API to a specific entity type
     * @var array
     */
    protected static $mapKeyToObjectType = array(
        'entries'                => 'feedEntry',
        'entry'                  => 'feedEntry',
        'technical_informations' => 'technical',
        'thumbnail'              => 'subdef',
        'items'                  => 'feedEntryItem',
        'item'                   => 'feedEntryItem',
        'record'                 => 'record',
        'records'                => 'record',
        'results'                => 'result',
        'permalink'              => 'permalink',
        'databox_status'         => 'databoxStatus',
        'databox_collection'     => 'databoxCollection',
        'quarantine_session'     => 'quarantineSession',
        'suggestions'            => 'querySuggestion',
        'basket_elements'        => 'basketElement',
        'validation_users'       => 'basketValidationParticipant',
        'validation_user'        => 'basketValidationParticipant',
        'validation_choices'     => 'basketValidationChoice',
        'termsOfuse'             => 'cgus',
        'stories'                => 'story',
        'story'                  => 'story',
        'story-metadata-bag'     => 'storyMetadataBag',
    );

    /**
     * Construct a new entity object
     *
     * @param  string                                $type the type of the entity
     * @param  string                                $em   the entity manager
     * @return \PhraseanetSDK\Entity\EntityInterface
     * @throws Exception\InvalidArgumentException    when types is unknown
     */
    public static function build($type, EntityManager $em)
    {
        if (isset(self::$mapKeyToObjectType[$type])) {
            $type = self::$mapKeyToObjectType[$type];
        }

        $namespace = '\\PhraseanetSDK\\Entity';

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
