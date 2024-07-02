<?php

namespace App\Cache;

use Illuminate\Support\Facades\Redis;

class LRUCacheRedis extends LRUCache
{
    private string $listKey;

    private string $mapKey;

    public function __construct(int $capacity, string $listKey, string $mapKey)
    {
        parent::__construct($capacity);

        $this->listKey = $listKey;

        $this->mapKey = $mapKey;
    }

    public function put(string $key, mixed $value): void
    {
        if (Redis::llen($this->listKey) >= $this->capacity) {
            $removedKey = Redis::lpop($this->listKey);

            Redis::hdel($this->mapKey, $removedKey);
        }

        Redis::rpush($this->listKey, $key);

        Redis::hset($this->mapKey, $key, $value);
    }

    public function get(string $key): mixed
    {
        $value = Redis::hget($this->mapKey, $key);

        if (!$value) {
            return null;
        }

        Redis::lrem($this->listKey, 0, $key);

        Redis::rpush($this->listKey, $key);

        return $value;
    }
}