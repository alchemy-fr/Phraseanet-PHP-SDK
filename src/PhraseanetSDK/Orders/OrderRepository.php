<?php

/*
 * This file is part of phraseanet/php-sdk.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhraseanetSDK\Orders;

use PhraseanetSDK\AbstractRepository;
use PhraseanetSDK\Exception\RuntimeException;

class OrderRepository extends AbstractRepository
{

    public function listOrders($pageIndex = 0, $pageSize = 20)
    {
        $parameters = [
            'page' => max($pageIndex, 0),
            'per_page' => max($pageSize, 1),
            'includes' => [ 'elements' ]
        ];

        $response = $this->query('POST', 'v2/orders/', array(), $parameters);

        if ($response->isEmpty()) {
            throw new RuntimeException('Response content is empty');
        }

        if (! $response->hasProperty('data')) {
            throw new RuntimeException('Missing \'data\' property in response');
        }

        return new OrderList($response->getProperty('data'), $response->getProperty('pagination'));
    }
}
