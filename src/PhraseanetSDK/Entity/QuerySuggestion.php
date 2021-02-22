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

use stdClass;

class QuerySuggestion
{
    /**
     * @param stdClass[] $values
     * @return QuerySuggestion[]
     */
    public static function fromList(array $values): array
    {
        $suggestions = array();

        foreach ($values as $value) {
            $suggestions[] = self::fromValue($value);
        }

        return $suggestions;
    }

    /**
     * @param stdClass $value
     * @return QuerySuggestion
     */
    public static function fromValue(stdClass $value): QuerySuggestion
    {
        return new self($value);
    }

    /**
     * @var stdClass
     */
    protected $source;

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
     * Get the suggestion value
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->source->value;
    }

    /**
     * Tell whether the suggestion is current
     *
     * @return Boolean
     */
    public function isCurrent(): bool
    {
        return $this->source->current;
    }

    /**
     * Get the suggestion hit
     *
     * @return integer
     */
    public function getHits(): int
    {
        return $this->source->hits;
    }
}
