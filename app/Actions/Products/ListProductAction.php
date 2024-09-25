<?php

namespace App\Actions\Products;

use App\Contracts\ListProductActionInterface;
use App\Contracts\Repositories\ProductRepositoryInterface;

class ListProductAction implements ListProductActionInterface
{
    public function __construct(
        private ProductRepositoryInterface $productRepository
    ) {}

    public function execute()
    {
        return $this->productRepository->index();
    }
}
