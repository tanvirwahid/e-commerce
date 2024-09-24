<?php

namespace App\Repositories;

use App\Contracts\Repositories\OrderItemRepositoryInterface;
use App\Models\OrderItem;

class OrderItemRepository implements OrderItemRepositoryInterface
{
    public function bulkInsert(array $bulkInsetData)
    {
        OrderItem::insert($bulkInsetData);
    }
}
