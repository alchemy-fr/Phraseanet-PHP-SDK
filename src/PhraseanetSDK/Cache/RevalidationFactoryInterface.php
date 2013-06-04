<?php

/*
 * This file is part of Phraseanet SDK.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhraseanetSDK\Cache;

use Guzzle\Plugin\Cache\RevalidationInterface;
use PhraseanetSDK\Exception\RuntimeException;

interface RevalidationFactoryInterface
{
    /**
     * Creates a RevalidationInterface
     *
     * @param string $type
     *
     * @return RevalidationInterface
     *
     * @throws RuntimeException
     */
    public function create($type);
}
