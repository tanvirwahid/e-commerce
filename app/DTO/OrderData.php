<?php

namespace App\DTO;

use Illuminate\Http\Request;

class OrderData
{
    public float $total_amount;

    public int $user_id;

    public function __construct(
        /** @var OrderItemData[] */
        public array $order_items
    ) {
        $this->user_id = auth()->id();
    }

    public static function fromRequest(Request $request): self
    {
        $orderItems = [];

        foreach ($request->get('items') as $item) {
            $orderItems[] = OrderItemData::make(
                $item['product_id'],
                $item['quantity']
            );
        }

        return new self($orderItems);
    }
}
