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

use Doctrine\Common\Collections\ArrayCollection;
use stdClass;

class DataboxDocumentStructure
{

    /**
     * @param stdClass[] $values
     * @return DataboxDocumentStructure[]
     */
    public static function fromList(array $values): array
    {
        $structures = array();

        foreach ($values as $value) {
            $structures[$value->id] = self::fromValue($value);
        }

        return $structures;
    }

    /**
     * @param stdClass $value
     * @return DataboxDocumentStructure
     */
    public static function fromValue(stdClass $value): DataboxDocumentStructure
    {
        return new self($value);
    }

    /**
     * @var stdClass
     */
    protected $source;

    /**
     * @var ArrayCollection
     */
    protected $labels;

    /**
     * @param stdClass $source
     */
    public function __construct(stdClass $source)
    {
        $this->source = $source;
    }

    /**
     * @return stdClass
     */
    public function getRawData(): stdClass
    {
        return $this->source;
    }

    /**
     * The documentary field id
     *
     * @return integer
     */
    public function getId(): int
    {
        return $this->source->id;
    }

    /**
     * The documentary field metadata namespace
     *
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->source->namespace;
    }

    /**
     * The documentary field metadata source
     *
     * @return string
     */
    public function getSource(): string
    {
        return $this->source->source;
    }

    /**
     * The documentary field metadata tagname
     *
     * @return string
     */
    public function getTagName(): string
    {
        return $this->source->tagname;
    }

    /**
     * The documentary field name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->source->name;
    }

    /**
     * The multi value field separator
     *
     * @return string
     */
    public function getSeparator(): string
    {
        return $this->source->separator;
    }

    /**
     * Get the associated thesaurus branch
     *
     * @return string
     */
    public function getThesaurusBranch(): string
    {
        return $this->source->thesaurus_branch;
    }

    /**
     * get the type of the field values
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->source->type;
    }

    /**
     * Tell whether the field is searchable
     *
     * @return Boolean
     */
    public function isSearchable(): bool
    {
        return $this->source->indexable;
    }

    /**
     * Tell whether the field is multivalued or not
     *
     * @return Boolean
     */
    public function isMultivalued(): bool
    {
        return $this->source->multivalue;
    }

    /**
     * Tell whether the field is a read only field
     *
     * @return Boolean
     */
    public function isReadonly(): bool
    {
        return $this->source->readonly;
    }

    /**
     * Tell whether the field is required
     *
     *  @return Boolean
     */
    public function isRequired(): bool
    {
        return $this->source->required;
    }

    /**
     * @return ArrayCollection
     */
    public function getLabels(): ArrayCollection
    {
        return $this->labels ?: $this->labels = new ArrayCollection((array) $this->source->labels);
    }
}
