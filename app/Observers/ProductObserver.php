<?php

namespace App\Observers;

use App\Actions\Products\ListCachedProductAction;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class ProductObserver
{
    public function created(Product $product)
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
