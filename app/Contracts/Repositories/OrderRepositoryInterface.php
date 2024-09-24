<?php

namespace App\Contracts\Repositories;

use App\DTO\OrderData;
use App\Models\Order;

interface OrderRepositoryInterface
{
    public function getAuthenticatedUsersOrders();

    public function create(OrderData $orderData): Order;
}
