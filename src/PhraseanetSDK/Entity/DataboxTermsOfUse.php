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
use PhraseanetSDK\Annotation\Id as Id;

class DataboxTermsOfUse
{

    public static function fromList(array $values)
    {
        $terms = array();

        foreach ($values as $value) {
            $terms[] = self::fromValue($value);
        }

        return $terms;
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
     * @return string
     */
    public function getLocale()
    {
        return $this->source->locale;
    }

    /**
     * @return string
     */
    public function getTerms()
    {
        return $this->source->terms;
    }
}
