<?php

namespace App\Actions\Products;

use App\Contracts\Repositories\ProductRepositoryInterface;

class ListProductAction
{
    public function __construct(
        private ProductRepositoryInterface $productRepository
    ) {}

    public function execute()
    {
        return $this->productRepository->index();
    }
}
