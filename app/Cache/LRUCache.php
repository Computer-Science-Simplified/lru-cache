<?php

namespace App\Cache;

abstract class LRUCache
{
    public function __construct(protected int $capacity)
    {
    }

    abstract public function put(string $key, mixed $value): void;

    abstract public function get(string $key): mixed;

    /**
     * @return array<int, mixed>
     */
    abstract public function getAll(?callable $callback = null): array;
}
