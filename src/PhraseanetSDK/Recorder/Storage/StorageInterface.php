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

interface StorageInterface extends \Countable
{
    /**
     * Fetchs data from storage
     *
     * @return array
     */
    public function fetch();

    /**
     * Saves data in storage
     *
     * @param array $data
     */
    public function save(array $data);
}
