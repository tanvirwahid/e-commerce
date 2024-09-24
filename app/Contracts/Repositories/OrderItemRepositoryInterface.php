<?php

namespace App\Contracts\Repositories;

interface OrderItemRepositoryInterface
{
    public function bulkInsert(array $bulkInsetData);
}
