<?php

namespace App\Observers;

use App\Actions\Products\ListCachedProductAction;
use App\CacheInvalidators\ProductCreationCacheInvalidator;
use App\CacheInvalidators\ProductUpdateCacheInvalidator;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ProductObserver
{
    public function __construct(
        private ProductCreationCacheInvalidator $creationCacheInvalidator,
        private ProductUpdateCacheInvalidator   $updateCacheInvalidator
    )
    {
    }

    public function creating()
    {
        $this->creationCacheInvalidator->invalidateCache();
    }

    public function updated(Product $product)
    {
        $this->updateCacheInvalidator->invalidateCache($product);
    }
}
