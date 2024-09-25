<?php

namespace App\Providers;

use App\Actions\Products\Factories\ListProductActionFactory;
use App\Contracts\ListProductActionFactoryInterface;
use App\Models\Product;
use App\Observers\ProductObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            ListProductActionFactoryInterface::class,
            ListProductActionFactory::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    }
}
