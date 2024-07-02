<?php

namespace App\Cache;

use Illuminate\Support\Facades\Redis;

/**
 * O(log(N)
 */
class LRUCacheRedisSet extends LRUCache
{
    private string $setKey;

    private string $mapKey;

    public function __construct(int $capacity, string $setKey, string $mapKey)
    {
        parent::__construct($capacity);

        $this->setKey = $setKey;

        $this->mapKey = $mapKey;
    }

    public function put(string $key, mixed $value): void
    {
        if (Redis::zcard($this->setKey) >= $this->capacity) {
            $result = Redis::zpopmin($this->setKey);

            $keys = array_keys($result);

            Redis::hdel($this->mapKey, $keys[0]);
        }

        Redis::zadd($this->setKey, now()->getPreciseTimestamp(), $key);

        Redis::hset($this->mapKey, $key, $value);
    }

    public function get(string $key): mixed
    {
        $value = Redis::hget($this->mapKey, $key);

        if (!$value) {
            return null;
        }

        Redis::zadd($this->setKey, now()->getPreciseTimestamp(), $key);

        return $value;
    }

    /**
     * @return array<int, mixed>
     */
    public function getAll(?callable $action = null): array
    {
        $items = Redis::hgetall($this->mapKey);

        if ($action) {
            return collect($items)
                ->map(fn ($item) => $action($item))
                ->all();
        }

        return $items;
    }
}
