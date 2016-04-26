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
        // 't' param is used for cache busting
        $parameters = [
            'page' => max($pageIndex, 0),
            'per_page' => max($pageSize, 1),
            'includes' => [ 'elements' ],
            't' => time()
        ];

        $response = $this->query('GET', 'v2/orders/', $parameters);

        if ($response->isEmpty()) {
            throw new RuntimeException('Response content is empty');
        }

        if (! $response->hasProperty('data')) {
            throw new RuntimeException('Missing \'data\' property in response');
        }

        if (! $response->hasProperty('meta')) {
            throw new RuntimeException('Missing \'meta\' property in response');
        }

        $meta = $response->getProperty('meta');

        return new OrderList($response->getProperty('data'), $meta->pagination);
    }

    public function createOrder($usage, array $recordsIds)
    {
        $parameters = [
            'usage' => $usage,
            'deadline' => '',
            'records' => $recordsIds
        ];

        $response = $this->query('POST', 'v2/orders/', [], [ 'data' => $parameters ], [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ]);
        
        return new Order($response->getProperty('data'));
    }
}
