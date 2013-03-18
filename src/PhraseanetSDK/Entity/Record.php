<?php

namespace PhraseanetSDK\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class Record extends AbstractEntity implements EntityInterface
{
    protected $recordId;
    protected $databoxId;
    protected $title;
    protected $mimeType;
    protected $originalName;
    protected $updatedOn;
    protected $createdOn;
    protected $collectionId;
    protected $sha256;
    protected $thumbnail;
    protected $technicalInformations;
    protected $phraseaType;
    protected $uuid;
    protected $metadatas;
    protected $subdefs;

    /**
     * Get the record id
     *
     * @return integer
     */
    public function getRecordId()
    {
        return $this->recordId;
    }

    public function setRecordId($recordId)
    {
        $this->recordId = $recordId;
    }

    /**
     * Get the databox id
     *
     * @return integer
     */
    public function getDataboxId()
    {
        return $this->databoxId;
    }

    public function setDataboxId($databoxId)
    {
        $this->databoxId = $databoxId;
    }

    /**
     * Get the record title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get the record mime type
     *
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;
    }

    /**
     * Get the record original name
     *
     * @return string
     */
    public function getOriginalName()
    {
        return $this->originalName;
    }

    public function setOriginalName($originalName)
    {
        $this->originalName = $originalName;
    }

    /**
     * Last updated date
     *
     * @return \DateTime
     */
    public function getUpdatedOn()
    {
        return $this->updatedOn;
    }

    public function setUpdatedOn(\DateTime $updatedOn)
    {
        $this->updatedOn = $updatedOn;
    }

    /**
     * Creation date
     *
     * @return \DateTime
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    public function setCreatedOn(\DateTime $createdOn)
    {
        $this->createdOn = $createdOn;
    }

    /**
     * Get the record collection id
     *
     * @return integer
     */
    public function getCollectionId()
    {
        return $this->collectionId;
    }

    public function setCollectionId($collectionId)
    {
        $this->collectionId = $collectionId;
    }

    /**
     * Get the record SHA256 hash
     *
     * @return string
     */
    public function getSha256()
    {
        return $this->sha256;
    }

    public function setSha256($sha256)
    {
        $this->sha256 = $sha256;
    }

    /**
     * Return the thumbnail of the record as a PhraseanetSDK\Entity\Subdef object
     * if the thumbnail exists null otherwise
     *
     * @return Subdef|null
     */
    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    public function setThumbnail(Subdef $thumbnail)
    {
        $this->thumbnail = $thumbnail;
    }

    /**
     * Get the Record phraseaType IMAGE|VIDEO|DOCUMENT etc..
     *
     * @return string
     */
    public function getPhraseaType()
    {
        return $this->phraseaType;
    }

    public function setPhraseaType($phraseaType)
    {
        $this->phraseaType = $phraseaType;
    }

    /**
     * Get the record UUID
     *
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    public function setUuid($uuid)
    {
        $this->uuid = $uuid;
    }

    /**
     * Get a collection of Phraseanet\Entity\Technical data objects
     *
     * @return ArrayCollection
     */
    public function getTechnicalInformations()
    {
        return $this->technicalInformations;
    }

    public function setTechnicalInformations(ArrayCollection $technicalInformations)
    {
        $this->technicalInformations = $technicalInformations;
    }

    /**
     * Return a collection of PhraseanetSDK\Entity\Subdef for the record
     *
     * Precise a name to get the desired subdef identified by its name
     *
     * /!\ This method requests the API
     *
     * @param  string|null            $name The desired subdef name
     * @return ArrayCollection|Subdef
     *
     * @throws NotFoundException In case the subdef name could not be found
     */
    public function getSubdefs($name = null)
    {
        if (null !== $name) {
            return $this->em->getRepository('subdef')->findByRecordAndName(
                $this->getDataboxId(),
                $this->getRecordId(),
                $name
            );
        }

        return $this->em->getRepository('subdef')->findByRecord(
            $this->getDataboxId(),
            $this->getRecordId()
        );
    }

    /**
     * Return a collection of PhraseanetSDK\Entity\Subdef for the record
     *
     * Precise an array of devices or mime types for the desired sub definitions
     *
     * /!\ This method requests the API
     *
     * @param  array            $devices The desired devices
     * @param  array            $mimes The desired mimes type
     * @return ArrayCollection
     *
     * @throws RuntimeException in case of response not valid
     */
    public function getSubdefsByDevicesAndMimeTypes(array $devices, array $mimes)
    {
        return $this->em->getRepository('subdef')->findByRecord(
            $this->getDataboxId(),
            $this->getRecordId(),
            $devices,
            $mimes
        );
    }

    /**
     * Return the record metdatas as a collection of PhraseanetSDK\Entity\Metadatas objects
     *
     * /!\ This method requests the API
     *
     * @return ArrayCollection
     */
    public function getMetadatas()
    {
        return $this->em->getRepository('metadatas')->findByRecord($this->getDataboxId(), $this->getRecordId());
    }

    /**
     * Get the record caption as collection of PhraseanetSDK\Entity\RecordCaption objects
     *
     * /!\ This method requests the API
     *
     * @return ArrayCollection
     */
    public function getCaption()
    {
        return $this->em->getRepository('caption')->findByRecord($this->getDataboxId(), $this->getRecordId());
    }

    /**
     * Get the record status as collection of PhraseanetSDK\Entity\RecordStatus objects
     *
     * /!\ This method requests the API
     *
     * @return ArrayCollection
     */
    public function getStatus()
    {
        return $this->em->getRepository('recordStatus')->findByRecord($this->getDataboxId(), $this->getRecordId());
    }
}
