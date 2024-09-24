<?php

namespace App\Actions\Products;

use App\Contracts\Repositories\ProductRepositoryInterface;
use App\DTO\ProductData;
use App\Models\Product;

class UpdateProductAction
{
    public function __construct(
        private ProductRepositoryInterface $productRepository
    ) {}

    public function execute(Product $product, ProductData $productData): Product
    {
        return $this->productRepository->update($product, $productData);
    }
}
