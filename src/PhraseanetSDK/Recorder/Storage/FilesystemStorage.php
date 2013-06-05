<?php

/*
 * This file is part of Phraseanet SDK.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhraseanetSDK\Recorder\Storage;

use PhraseanetSDK\Exception\RuntimeException;

class FilesystemStorage implements StorageInterface
{
    private $file;

    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * {@inheritdoc}
     */
    public function fetch()
    {
        if (false === $content = @file_get_contents($this->file)) {
            return array();
        }

        $data = @json_decode($content, true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            return array();
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function save(array $data)
    {
        $option = defined('JSON_PRETTY_PRINT') ? JSON_PRETTY_PRINT : 0;

        if (false === @file_put_contents($this->file, json_encode($data, $option))) {
            throw new RuntimeException('Unable to save data to file');
        }
    }
}
