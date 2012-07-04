<?php

namespace PhraseanetSDK\Repository;

use PhraseanetSDK\Exception\RuntimeException;
use PhraseanetSDK\Exception\NotFoundException;
use PhraseanetSDK\Entity\Record as RecordEntity;
use Doctrine\Common\Collections\ArrayCollection;

class Subdef extends AbstractRepository
{

    /**
     * Find all subdefs that belong to the provided record
     *
     * @param  integer          $databoxId The databox id
     * @param  integer          $recordId  The record id
     * @param  integer          $devices   an array of desired devices
     * @param  integer          $mimes     an array of desired mimetypes
     * @return ArrayCollection
     * @throws RuntimeException
     */
    public function findByRecord($databoxId, $recordId, $devices = array(), $mimes = array())
    {
        $parameters = array();

        if ( ! ! count($devices)) {
            $parameters['devices'] = $devices;
        }

        if ( ! ! count($mimes)) {
            $parameters['mimes'] = $mimes;
        }

        $response = $this->query('GET', sprintf('/records/%d/%d/embed/', $databoxId, $recordId), $parameters);

        if (true !== $response->hasProperty('embed')) {
            throw new RuntimeException('Missing "embed" property in response content');
        }

        $subdefCollection = new ArrayCollection();

        foreach ($response->getProperty('embed') as $subdefDatas) {
            $subdef = $this->em->hydrateEntity($this->em->getEntity('subdef'), $subdefDatas);
            $subdefCollection->add($subdef);
        }

        return $subdefCollection;
    }

    /**
     * Find a subdefs by its name that belong to the provided record
     *
     * @param  RecordEntity                $record The provided record
     * @param  string                      $name   The provided name
     * @return PreaseanetSDL\Entity\Subdef
     * @throws NotFoundException
     */
    public function findByRecordAndName($databoxId, $recordId, $name)
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
