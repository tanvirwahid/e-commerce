<?php

namespace App\Actions\Products;

use App\Contracts\Repositories\ProductRepositoryInterface;
use App\DTO\ProductData;
use App\Models\Product;

class CreateProductAction
{
    public function __construct(
        private ProductRepositoryInterface $productRepository
    ) {}

    public function execute(ProductData $productData): Product
    {
        return $this->productRepository->create($productData);
    }
}
