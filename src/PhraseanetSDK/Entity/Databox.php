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

class Databox extends AbstractEntity
{
    /** @var integer */
    protected $databoxId;

    /** @var string */
    protected $name;

    /** @var string */
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
