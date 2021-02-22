<?php

/*
 * This file is part of Phraseanet SDK.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhraseanetSDK\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use PhraseanetSDK\AbstractRepository;
use PhraseanetSDK\Entity\Subdef as SubdefEntity;
use PhraseanetSDK\Exception\NotFoundException;
use PhraseanetSDK\Exception\RuntimeException;
use PhraseanetSDK\Exception\TokenExpiredException;
use PhraseanetSDK\Exception\UnauthorizedException;

class Subdef extends AbstractRepository
{
    /**
     * Find all subdefs that belong to the provided record
     *
     * @param integer $databoxId The databox id
     * @param integer $recordId  The record id
     * @param string[] $devices  an array of desired devices
     * @param string[] $mimes    an array of desired mimetypes
     * @return ArrayCollection
     * @throws RuntimeException
     * @throws UnauthorizedException
     * @throws TokenExpiredException
     * @throws NotFoundException
     */
    public function findByRecord(int $databoxId, int $recordId, $devices = array(), $mimes = array()): ArrayCollection
    {
        $parameters = array();

        if (count($devices)) {
            $parameters['devices'] = $devices;
        }

        if (count($mimes)) {
            $parameters['mimes'] = $mimes;
        }

        $response = $this->query('GET', sprintf('v1/records/%d/%d/embed/', $databoxId, $recordId), $parameters);

        if (true !== $response->hasProperty('embed')) {
            throw new RuntimeException('Missing "embed" property in response content');
        }

        return new ArrayCollection(SubdefEntity::fromList($response->getProperty('embed')));
    }

    /**
     * Find a subdefs by its name that belong to the provided record
     *
     * @param integer $databoxId The record databoxId
     * @param integer $recordId  The recordId
     * @param string $name       The name of the subdef
     * @return SubdefEntity
     * @throws RuntimeException
     * @throws UnauthorizedException
     * @throws TokenExpiredException
     * @throws NotFoundException
     */
    public function findByRecordAndName(int $databoxId, int $recordId, string $name): SubdefEntity
    {
        $subdefs = $this->findByRecord($databoxId, $recordId);

        foreach ($subdefs as $subdef) {
            if ($subdef->getName() === $name) {
                return $subdef;
            }
        }

        throw new NotFoundException(sprintf('%s subdef name not found', $name));
    }
}
