<?php

namespace App\Actions\Products;

use App\Contracts\ListProductActionInterface;
use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class ListCachedProductAction implements ListProductActionInterface
{
    const PRODUCT_PAGES_CACHE_KEY = 'product_cache_keys';

    public function __construct(
        private ProductRepositoryInterface $productRepository
    ) {}

    public function execute()
    {
        $page = request()->filled('page') ? request()->get('page') : 1;

        $cacheKey = 'products_'. Product::PER_PAGE.'_'.$page;

        $allCachedPages = Cache::get(self::PRODUCT_PAGES_CACHE_KEY);
        $cachedPages = $allCachedPages ? $allCachedPages : [];

        $cachedPages[] = $page;

        $cachedPages = array_unique($cachedPages);
        Cache::put(self::PRODUCT_PAGES_CACHE_KEY, $cachedPages, Product::CACHE_TTL + 60);

        return Cache::remember(
            $cacheKey, Product::CACHE_TTL,
            function () {
                return $this->productRepository->index();
            }
        );
    }

}
