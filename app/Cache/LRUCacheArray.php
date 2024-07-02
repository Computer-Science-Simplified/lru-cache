<?php

namespace App\Cache;

/**
 * O(N)
 */
class LRUCacheArray extends LRUCache
{
    /** @var array <string, mixed> */
    private array $map = [];

    private array $keys = [];

    public function put(string $key, mixed $value): void
    {
        if (count($this->keys) >= $this->capacity) {
            $removedKey = array_pop($this->keys);

            unset($this->map[$removedKey]);
        }

        array_unshift($this->keys, $key);

        $this->map[$key] = $value;
    }

    public function get(string $key): mixed
    {
        if (!isset($this->map[$key])) {
            return null;
        }

        $this->update($key);

        return $this->map[$key];
    }

    private function update(string $key): void
    {
        $idx = array_search($key, $this->keys);

        if ($idx === false) {
            return;
        }

        unset($this->keys[$idx]);

        $this->keys = array_values($this->keys);

        array_unshift($this->keys, $key);
    }
}
