<?php

/*
 * This file is part of Phraseanet SDK.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhraseanetSDK\Repository;

use PhraseanetSDK\AbstractRepository;
use PhraseanetSDK\Exception\NotFoundException;
use PhraseanetSDK\Exception\RuntimeException;
use PhraseanetSDK\Exception\UnauthorizedException;

class MeCollection extends AbstractRepository
{
	/**
	 * Return all collections available
	 *
	 * @return MeCollection[]
	 * @throws NotFoundException
	 * @throws UnauthorizedException
	 */
	public function getCollectionsList()
	{
		$response = $this->query('GET', 'v1/me/collections/');

		if ($response->hasProperty(('collections')) !== true) {
			throw new RuntimeException('Missing "collections" property in response content');
		}

		return \PhraseanetSDK\Entity\MeCollection::fromList($response->getProperty('collections'));
	}
}
