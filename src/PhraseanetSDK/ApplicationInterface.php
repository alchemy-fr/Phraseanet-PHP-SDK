<?php

/*
 * This file is part of Phraseanet SDK.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhraseanetSDK;

interface ApplicationInterface
{
    /** @var string Phraseanet API mount point */
    const API_MOUNT_POINT = '/api/v1/';

    /** @var string SDK version */
    const VERSION = '0.3-dev';

    /**
     * Return the OAuth2Connector
     *
     * @return OAuth2Connector
     */
    public function getOauth2Connector();

    /**
     * Returns the entity manager given a token
     *
     * @param string $token
     *
     * @return EntityManager
     */
    public function getEntityManager($token);

    /**
     * Returns a monitor instance
     *
     * @param string $token
     *
     * @return Monitor
     */
    public function getMonitor($token);
}
