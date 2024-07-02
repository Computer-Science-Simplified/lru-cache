<?php

namespace App\Cache;

/**
 * O(N)
 */
class LRUCacheArray extends LRUCache
{
    /** @var array <string, mixed> */
    private array $map = [];

    private array $items = [];

    public function put(string $key, mixed $value): void
    {
        if (count($this->items) >= $this->capacity) {
            $removedKey = array_pop($this->items);

            unset($this->map[$removedKey]);
        }

        array_unshift($this->items, $key);

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
        $idx = array_search($key, $this->items);

        if ($idx === false) {
            return;
        }

        unset($this->items[$idx]);

        $this->items = array_values($this->items);

        array_unshift($this->items, $key);
    }

    public function getAll(?callable $action = null): array
    {
        if ($action) {
            return collect($this->map)
                ->map(fn ($item) => $action($item))
                ->all();
        }

        return $this->map;
    }
}
