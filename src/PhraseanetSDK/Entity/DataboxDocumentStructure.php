<?php

namespace PhraseanetSDK\Entity;

class DataboxDocumentStructure extends AbstractEntity implements EntityInterface
{
    protected $id;
    protected $namespace;
    protected $source;
    protected $tagname;
    protected $name;
    protected $separator;
    protected $thesaurusBranch;
    protected $type;
    protected $indexable;
    protected $multivalue;
    protected $readonly;
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

    public function setNamespace($namepsace)
    {
        $this->namespace = $namepsace;
    }

    /**
     * The documentary field metadatas source
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
     * The documentary field metadatas tagname
     *
     * @return string
     */
    public function getTagname()
    {
        return $this->tagname;
    }

    public function setTagname($tagname)
    {
        $this->tagname = $tagname;
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
     * The mutlivalue field separator
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
     * Tell whether the field is indexable
     *
     * @return Boolean
     */
    public function isIndexable()
    {
        return $this->indexable;
    }

    public function setIndexable($indexable)
    {
        $this->indexable = $indexable;
    }

    /**
     * Tell wheteher the field is ultivalued or not
     *
     * @return Boolean
     */
    public function isMultivalue()
    {
        return $this->multivalue;
    }

    public function setMultivalue($multivalue)
    {
        $this->multivalue = $multivalue;
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
     * Tell whether the firld is required
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
