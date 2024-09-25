<?php

namespace App\Observers;

use App\Actions\Products\ListCachedProductAction;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

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

    public function saving(Product $product)
    {
        $position = Product::where('id', '<=', $product->id)
            ->count();
        $pageNo = ceil($position / Product::PER_PAGE);
        Log::info($pageNo);

        Cache::forget('products_'.Product::PER_PAGE. '_'. $pageNo);
    }
}
