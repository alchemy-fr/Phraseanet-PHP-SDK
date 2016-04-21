<?php

/*
 * This file is part of alchemy/pipeline-component.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhraseanetSDK\Orders;

class OrderList 
{
    /**
     * @var \stdClass[]
     */
    private $source;

    /**
     * @var Order[]|null
     */
    private $orders;

    /**
     * @var int
     */
    private $currentPage;

    /**
     * @var int
     */
    private $totalPages;

    /**
     * @var int
     */
    private $perPage;

    /**
     * @var int
     */
    private $count;

    /**
     * @var int
     */
    private $totalCount;

    /**
     * @param array $source
     * @param \stdClass $pagination
     */
    public function __construct(array $source, \stdClass $pagination)
    {
        $this->source = $source;
        $this->currentPage = (int) $pagination->current_page;
        $this->totalPages = (int) $pagination->total_pages;
        $this->perPage = (int) $pagination->per_page;
        $this->totalCount = (int) $pagination->total;
        $this->count = (int) $pagination->count;
    }

    /**
     * @return Order[]
     */
    public function getOrders()
    {
        return $this->orders ?: $this->orders = Order::fromList($this->source);
    }

    /**
     * @return int
     */
    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    /**
     * @return int
     */
    public function getTotalPages()
    {
        return $this->totalPages;
    }

    /**
     * @return int
     */
    public function getPerPage()
    {
        return $this->perPage;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @return int
     */
    public function getTotalCount()
    {
        return $this->totalCount;
    }
}
