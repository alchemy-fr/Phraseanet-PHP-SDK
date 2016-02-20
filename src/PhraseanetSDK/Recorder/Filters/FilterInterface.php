<?php

/*
 * This file is part of Phraseanet SDK.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhraseanetSDK\Recorder\Filters;

interface FilterInterface
{
    /**
     * Apply the filter on data
     *
     * @param array $data
     */
    public function apply(array &$data);
}
