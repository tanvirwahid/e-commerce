<?php

namespace App\CacheInvalidators;

use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class ProductUpdateCacheInvalidator
{
    public function __construct(
        private ProductRepositoryInterface $productRepository
    ) {}

    public function invalidateCache(Product $product)
    {
        $position = $this->productRepository->getPositionFromId($product->id);
        $pageNo = ceil($position / Product::PER_PAGE);

        Cache::forget('products_'.Product::PER_PAGE. '_'. $pageNo);
    }
}
