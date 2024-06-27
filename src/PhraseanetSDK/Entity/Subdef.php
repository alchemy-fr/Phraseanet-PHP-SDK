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

        foreach ($values as $key => $value) {
            if ($value == null) {
                continue;
            }

            if (!is_int($key)) {
                $name = $key;
                $value->name = $name;
            } else {
                $name =  $value->name;
            }

            $subdefs[$name] = self::fromValue($value);
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
        return isset($this->source->size) ? $this->source->size : $this->source->filesize ;
    }

    /**
     * Get the player type
     *
     * @return string
     */
    public function getPlayerType()
    {
        if (!isset($this->source->player_type)) {
            return '';
        }

        return $this->source->player_type;
    }

    /**
     * Get subdef mime type
     *
     * @return string
     */
    public function getMimeType()
    {
        return isset($this->source->mime) ? $this->source->mime : $this->source->mime_type;
    }

    /**
     * Get the permalink related to the subdef
     *
     * @return Permalink
     */
    public function getPermalink()
    {
        if ($this->permalink) {
            return $this->permalink;
        } else {
            if (!is_object($this->source->permalink)) {
                $permalink = (object) ['url' => $this->source->permalink];
            } else {
                $permalink = $this->source->permalink;
            }

            return Permalink::fromValue($permalink);
        }
    }
}
