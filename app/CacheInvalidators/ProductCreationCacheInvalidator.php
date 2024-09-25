<?php

namespace App\CacheInvalidators;

use App\Actions\Products\ListCachedProductAction;
use App\Contracts\CacheInvalidatorInterface;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class ProductCreationCacheInvalidator implements CacheInvalidatorInterface
{
    public function invalidateCache()
    {
        $key = ListCachedProductAction::PRODUCT_PAGES_CACHE_KEY;

        $allCachedPages = Cache::get($key);
        
        $cachedPages = $allCachedPages ? $allCachedPages : [];

        foreach($cachedPages as $cachePageNo)
        {
            Cache::forget('products_'.Product::PER_PAGE. '_'. $cachePageNo);
        }
    }
}
