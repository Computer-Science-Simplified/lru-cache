<?php

namespace App\Benchmark;

use App\Cache\LRUCache;
use App\Cache\LRUCacheArray;
use App\Cache\LRUCacheLinkedList;
use App\Cache\LRUCacheRedisList;
use App\Cache\LRUCacheRedisSet;
use Illuminate\Support\Facades\Redis;

class Benchmark
{
    public function run()
    {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);

        Redis::flushall();

        $benchmark = function (LRUCache $cache, int $n) {
            foreach (range(1, $n) as $i) {
                $cache->put($i, $i);
            }

            foreach (range(1, $n) as $i) {
                $cache->get($i);
            }
        };

        \Illuminate\Support\Benchmark::dd([
            'array:1_000' => function () use ($benchmark) {
                $benchmark(new LRUCacheArray(1_000), 1_000);
            },
            'array:10_000' => function () use ($benchmark) {
                $benchmark(new LRUCacheArray(10_000), 10_000);
            },
            'linked_list:1_000' => function () use ($benchmark) {
                $benchmark(new LRUCacheLinkedList(1_000), 1_000);
            },
            'linked_list:10_000' => function () use ($benchmark) {
                $benchmark(new LRUCacheLinkedList(10_000), 10_000);
            },
            'redis_set:1_000' => function () use ($benchmark) {
                $benchmark(new LRUCacheRedisSet(1_000, 'items_set', 'cache_set'), 1_000);
            },
            'redis_set:10_000' => function () use ($benchmark) {
                $benchmark(new LRUCacheRedisSet(10_000, 'items_set', 'cache_set'), 10_000);
            },
            'redis_list:1_000' => function () use ($benchmark) {
                $benchmark(new LRUCacheRedisList(1_000, 'items_list', 'cache_list'), 1_000);
            },
            'redis_list:10_000' => function () use ($benchmark) {
                $benchmark(new LRUCacheRedisList(10_000, 'items_list', 'cache_list'), 10_000);
            },
        ]);
    }
}
