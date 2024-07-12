<?php

namespace App\Providers;

use App\Cache\LRUCache;
use App\Cache\LRUCacheRedisSet;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->bind(
            LRUCache::class,
            function () {
                return new LRUCacheRedisSet(
                    5,
                    'artist:recent:keys:' . request()->user()->id,
                    'artist:recent:cache:' . request()->user()->id,
                );
            },
        );
    }
}
