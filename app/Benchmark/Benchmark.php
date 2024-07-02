<?php

namespace App\Benchmark;

use App\Cache\LRUCache;
use App\Cache\LRUCacheArray;
use App\Cache\LRUCacheLinkedList;

class Benchmark
{
    public function run()
    {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);

        $benchmark = function (LRUCache $cache, int $n) {
            foreach (range(1, $n) as $i) {
                $cache->put($i, $i);
            }

            foreach (range(1, $n) as $i) {
                $cache->get($i);
            }
        };

        $result = \Illuminate\Support\Benchmark::measure([
            'array_1000' => function () use ($benchmark) {
                $benchmark(new LRUCacheArray(1_000), 1_000);
            },
            'array_10_000' => function () use ($benchmark) {
                $benchmark(new LRUCacheArray(10_000), 10_000);
            },
            'array_1_000_000' => function () use ($benchmark) {
                $benchmark(new LRUCacheArray(1_000_000), 1_000_000);
            },
            'linked_list_1000' => function () use ($benchmark) {
                $benchmark(new LRUCacheLinkedList(1_000), 1_000);
            },
            'linked_list_10_000' => function () use ($benchmark) {
                $benchmark(new LRUCacheLinkedList(10_000), 10_000);
            },
            'linked_list_1_000_000' => function () use ($benchmark) {
                $benchmark(new LRUCacheLinkedList(1_000_000), 1_000_000);
            },
        ]);

        dd($result);
    }
}
