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

class DataboxDocumentStructure
{
    /**
     *
     * @ApiField(bind_to="id", type="int")
     */
    protected $id;
    /**
     * @ApiField(bind_to="namespace", type="string")
     */
    protected $namespace;
    /**
     * @ApiField(bind_to="source", type="string")
     */
    protected $source;
    /**
     * @ApiField(bind_to="tagname", type="string")
     */
    protected $tagName;
    /**
     * @ApiField(bind_to="name", type="string")
     */
    protected $name;
    /**
     * @ApiField(bind_to="separator", type="string")
     */
    protected $separator;
    /**
     * @ApiField(bind_to="thesaurus_branch", type="string")
     */
    protected $thesaurusBranch;
    /**
     * @ApiField(bind_to="type", type="string")
     */
    protected $type;
    /**
     * @ApiField(bind_to="indexable", type="boolean")
     */
    protected $searchable;
    /**
     * @ApiField(bind_to="multivalue", type="boolean")
     */
    protected $multivalued;
    /**
     * @ApiField(bind_to="readonly", type="boolean")
     */
    protected $readonly;
    /**
     * @ApiField(bind_to="required", type="boolean")
     */
    protected $required;

    /**
     * The documentary field id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * The documentary field metadata namespace
     *
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * The documentary field metadata source
     *
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    public function setSource($source)
    {
        $this->source = $source;
    }

    /**
     * The documentary field metadata tagname
     *
     * @return string
     */
    public function getTagName()
    {
        return $this->tagName;
    }

    public function setTagName($tagName)
    {
        $this->tagName = $tagName;
    }

    /**
     * The documentary field name
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
     * The multi value field separator
     *
     * @return string
     */
    public function getSeparator()
    {
        return $this->separator;
    }

    public function setSeparator($separator)
    {
        $this->separator = $separator;
    }

    /**
     * Get the associated thesaurus branch
     *
     * @return string
     */
    public function getThesaurusBranch()
    {
        return $this->thesaurusBranch;
    }

    public function setThesaurusBranch($thesaurus_branch)
    {
        $this->thesaurusBranch = $thesaurus_branch;
    }

    /**
     * get the type of the field values
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Tell whether the field is searchable
     *
     * @return Boolean
     */
    public function isSearchable()
    {
        return $this->searchable;
    }

    public function setSearchable($searchable)
    {
        $this->searchable = $searchable;
    }

    /**
     * Tell whether the field is multivalued or not
     *
     * @return Boolean
     */
    public function isMultivalued()
    {
        return $this->multivalued;
    }

    public function setMultivalued($multivalued)
    {
        $this->multivalued = $multivalued;
    }

    /**
     * Tell whether the field is a read only field
     *
     * @return Boolean
     */
    public function isReadonly()
    {
        return $this->readonly;
    }

    public function setReadonly($readonly)
    {
        $this->readonly = $readonly;
    }

    /**
     * Tell whether the field is required
     *
     *  @return Boolean
     */
    public function isRequired()
    {
        return $this->required;
    }

    public function setRequired($required)
    {
        $this->required = $required;
    }
}
