<?php

namespace App\DTO;

use Illuminate\Http\Request;

class ProductData
{
    public function __construct(
        public string $name,
        public float $price,
        public int $stock,
        public int $created_by
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            $request->get('name'),
            $request->get('price'),
            $request->get('stock'),
            auth()->id()
        );
    }
}
