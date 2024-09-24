<?php

namespace App\Repositories;

use App\Contracts\Repositories\OrderRepositoryInterface;
use App\DTO\OrderData;
use App\Models\Order;

class OrderRepository implements OrderRepositoryInterface
{
    public function getAuthenticatedUsersOrders()
    {
        return Order::self()->with('items')->paginate(10);
    }

    public function create(OrderData $orderData): Order
    {
        return Order::create([
            'user_id' => $orderData->user_id,
            'total_amount' => $orderData->total_amount,
        ]);
    }
}
