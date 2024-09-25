<?php

namespace App\Actions\Products;

use App\Contracts\ListProductActionInterface;
use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class ListCachedProductAction implements ListProductActionInterface
{
    public function __construct(
        private ProductRepositoryInterface $productRepository
    ) {}

    public function execute()
    {
        $page = request()->get('page');
        return Cache::remember(
            'products_'. Product::PER_PAGE.'_'.$page, Product::PER_PAGE,
            function () {
                return $this->productRepository->index();
            }
        );
    }

}
