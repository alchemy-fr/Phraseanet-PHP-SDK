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
    /**
     * @var string Phraseanet API mount point
     */
    public const API_MOUNT_POINT = '/api';

    /**
     * @var string PHP SDK user agent
     */
    public const USER_AGENT = 'Phraseanet SDK';

    /**
     * @var string SDK version
     */
    public const VERSION = '1.x';

    /**
     * Return an OAuth2Connector
     *
     * @return OAuth2Connector
     */
    public function getOauth2Connector(): OAuth2Connector;

    /**
     * Returns a entity manager given a token
     *
     * @param string|null $token
     *
     * @return EntityManager
     */
    public function getEntityManager(?string $token): EntityManager;

    /**
     * Returns a monitor instance given a token
     *
     * @param string $token
     *
     * @return Monitor
     */
    public function getMonitor(string $token): Monitor;

    /**
     * Returns an uploader instance given a token
     *
     * @param string $token
     *
     * @return Uploader
     */
    public function getUploader(string $token): Uploader;
}
