<?php

namespace App\Actions\Products;

use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Models\Product;

class DeleteProductAction
{
    public function __construct(
        private ProductRepositoryInterface $productRepository
    )
    {
    }

    public function execute(Product $product)
    {
        $this->productRepository->delete($product);
    }
}
