<?php

namespace App\Providers;

use App\Cache\LRUCache;
use App\Cache\LRUCacheRedisSet;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->singleton(
            LRUCache::class,
            function () {
                return new LRUCacheRedisSet(
                    5,
                    'search:recent:keys:' . request()->user()->id,
                    'search:recent:cache:' . request()->user()->id,
                );
        });
    }
}
