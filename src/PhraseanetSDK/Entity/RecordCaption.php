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

use PhraseanetSDK\Annotation\ApiField as ApiField;
use PhraseanetSDK\Annotation\ApiRelation as ApiRelation;

class RecordCaption
{
    /**
     * @ApiField(bind_to="meta_structure_id", type="int")
     */
    protected $metaStructureId;
    /**
     * @ApiField(bind_to="name", type="string")
     */
    protected $name;
    /**
     * @ApiField(bind_to="value", type="string")
     */
    protected $value;

    /**
     * Get the related databox meta field id
     *
     * @return integer
     */
    public function getMetaStructureId()
    {
        return $this->metaStructureId;
    }

    public function setMetaStructureId($metaStructureId)
    {
        $this->metaStructureId = $metaStructureId;
    }

    /**
     * Get the name of the caption
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get the value of the caption
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }
}
