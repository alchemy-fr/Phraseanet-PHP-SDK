<?php

/*
 * This file is part of Phraseanet SDK.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhraseanetSDK\Utils;

class Camelizer
{
    public function camelize($input)
    {
        return implode('', array_map(function ($chunk) {
            return ucfirst($chunk);
        }, preg_split('/[-_]/', $input)));
    }
}
