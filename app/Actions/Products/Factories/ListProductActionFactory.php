<?php

namespace App\Actions\Products\Factories;

use App\Actions\Products\ListCachedProductAction;
use App\Actions\Products\ListProductAction;
use App\Contracts\ListProductActionFactoryInterface;
use App\Contracts\ListProductActionInterface;

class ListProductActionFactory implements ListProductActionFactoryInterface
{
    public function getListProductAction(): ListProductActionInterface
    {
        $noOfPagesToCache = config('product-list-cache.pages-to-cache');

        $page = request()->filled('page') ? request()->get('page') : 1;

        if($noOfPagesToCache == 'ALL' || $noOfPagesToCache >= $page)
        {
            return app()->make(ListCachedProductAction::class);
        }

        return app()->make(ListProductAction::class);
    }
}
