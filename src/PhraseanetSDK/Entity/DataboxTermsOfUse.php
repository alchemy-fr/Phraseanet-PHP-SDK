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

class DataboxTermsOfUse
{
    /**
     * @param stdClass[] $values
     * @return DataboxTermsOfUse[]
     */
    public static function fromList(array $values): array
    {
        $terms = array();

        foreach ($values as $value) {
            $terms[$value->locale] = self::fromValue($value);
        }

        return $terms;
    }

    /**
     * @param stdClass $value
     * @return DataboxTermsOfUse
     */
    public static function fromValue(stdClass $value): DataboxTermsOfUse
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
     * @return string
     */
    public function getLocale(): string
    {
        return $this->source->locale;
    }

    /**
     * @return string
     */
    public function getTerms(): string
    {
        return $this->source->terms;
    }
}
