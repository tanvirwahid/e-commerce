<?php

namespace App\DTO;

class OrderItemData
{
    public int $order_id;
    public float $price;

    public function __construct(
        public int $product_id,
        public int $quantity
    )
    {
    }

    public static function make(int $productId, int $quantity): self
    {
        return new self(
            $productId,
            $quantity
        );
    }
}
