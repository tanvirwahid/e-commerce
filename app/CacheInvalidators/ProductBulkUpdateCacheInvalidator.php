<?php

namespace App\CacheInvalidators;

use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Entities\Products\ProductIds;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class ProductBulkUpdateCacheInvalidator
{
    public function __construct(
        private ProductRepositoryInterface $productRepository
    ) {}

    public function invalidateCache(ProductIds $productIds)
    {
        $perPage = Product::PER_PAGE;

        foreach (
            $this->productRepository
                ->getBulkPositions($productIds)
                ->getPositions() as $position
        )
        {
            Cache::forget('products_'.$perPage. '_'. ceil($position / $perPage));
        }
    }
}
