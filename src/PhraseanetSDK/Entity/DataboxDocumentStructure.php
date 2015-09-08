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

class DataboxDocumentStructure
{

    public static function fromList(array $values)
    {
        $structures = array();

        foreach ($values as $value) {
            $structures[] = self::fromValue($value);
        }

        return $structures;
    }

    public static function fromValue(\stdClass $value)
    {
        return new self($value);
    }

    /**
     * @var \stdClass
     */
    protected $source;

    /**
     * @param \stdClass $source
     */
    public function __construct(\stdClass $source)
    {
        $this->source = $source;
    }

    /**
     * The documentary field id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->source->id;
    }

    /**
     * The documentary field metadata namespace
     *
     * @return string
     */
    public function getNamespace()
    {
        return $this->source->namespace;
    }

    /**
     * The documentary field metadata source
     *
     * @return string
     */
    public function getSource()
    {
        return $this->source->source;
    }

    /**
     * The documentary field metadata tagname
     *
     * @return string
     */
    public function getTagName()
    {
        return $this->source->tagname;
    }

    /**
     * The documentary field name
     *
     * @return string
     */
    public function getName()
    {
        return $this->source->name;
    }

    /**
     * The multi value field separator
     *
     * @return string
     */
    public function getSeparator()
    {
        return $this->source->separator;
    }

    /**
     * Get the associated thesaurus branch
     *
     * @return string
     */
    public function getThesaurusBranch()
    {
        return $this->source->thesaurus_branch;
    }

    /**
     * get the type of the field values
     *
     * @return string
     */
    public function getType()
    {
        return $this->source->type;
    }

    /**
     * Tell whether the field is searchable
     *
     * @return Boolean
     */
    public function isSearchable()
    {
        return $this->source->indexable;
    }

    /**
     * Tell whether the field is multivalued or not
     *
     * @return Boolean
     */
    public function isMultivalued()
    {
        return $this->source->multivalue;
    }

    /**
     * Tell whether the field is a read only field
     *
     * @return Boolean
     */
    public function isReadonly()
    {
        return $this->source->readonly;
    }

    /**
     * Tell whether the field is required
     *
     *  @return Boolean
     */
    public function isRequired()
    {
        return $this->source->required;
    }

    /**
     * @return mixed
     */
    public function getLabels()
    {
        return $this->source->labels;
    }
}
