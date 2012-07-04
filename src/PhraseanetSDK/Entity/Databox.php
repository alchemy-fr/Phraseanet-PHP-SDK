<?php

namespace PhraseanetSDK\Entity;

class Databox extends AbstractEntity implements EntityInterface
{
    /**
     *
     * @var integer
     */
    protected $databoxId;

    /**
     *
     * @var string
     */
    protected $name;

    /**
     *
     * @var string
     */
    protected $version;

    /**
     * the databox id
     *
     * @return integer
     */
    public function getDataboxId()
    {
        return $this->databoxId;
    }

    /**
     *
     * @param integer $databoxId
     */
    public function setDataboxId($databoxId)
    {
        $this->databoxId = $databoxId;
    }

    /**
     * The databox name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * The databox version
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     *
     * @param string $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }
}
