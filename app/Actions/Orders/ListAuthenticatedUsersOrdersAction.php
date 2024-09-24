<?php

namespace App\Actions\Orders;

use App\Contracts\Repositories\OrderRepositoryInterface;

class ListAuthenticatedUsersOrdersAction
{
    public function __construct(private OrderRepositoryInterface $orderRepository)
    {
    }

    public function execute()
    {
        return $this->orderRepository->getAuthenticatedUsersOrders();
    }
}
