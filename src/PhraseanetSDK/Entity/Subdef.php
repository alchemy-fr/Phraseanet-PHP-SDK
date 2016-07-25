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
use PhraseanetSDK\Annotation\Id as Id;

class Subdef
{

    public static function fromList(array $values)
    {
        $subdefs = array();

        foreach ($values as $value) {
            if ($value == null) {
                continue;
            }

            $subdefs[$value->name] = self::fromValue($value);
        }

        return $subdefs;
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
     * @var Permalink
     */
    protected $permalink;

    /**
     * @param \stdClass $source
     */
    public function __construct(\stdClass $source)
    {
        $this->source = $source;
    }

    /**
     * @return \stdClass
     */
    public function getRawData()
    {
        return $this->source;
    }

    /**
     * @return \stdClass
     * @deprecated Use getRawData() instead
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Get subdef name
     *
     * @return string
     */
    public function getName()
    {
        return $this->source->name;
    }

    /**
     * Get subdef height
     *
     * @return integer
     */
    public function getHeight()
    {
        return $this->source->height;
    }

    /**
     * Get subdef width
     *
     * @return integer
     */
    public function getWidth()
    {
        return $this->source->width;
    }

    /**
     * Get subdef file size
     *
     * @return integer
     */
    public function getFileSize()
    {
        return $this->source->filesize;
    }

    /**
     * Get the player type
     *
     * @return string
     */
    public function getPlayerType()
    {
        return $this->source->player_type;
    }

    /**
     * Get subdef mime type
     *
     * @return string
     */
    public function getMimeType()
    {
        return $this->source->mime_type;
    }

    /**
     * Get the permalink related to the subdef
     *
     * @return Permalink
     */
    public function getPermalink()
    {
        return $this->permalink ?: $this->permalink = Permalink::fromValue($this->source->permalink);
    }
}
