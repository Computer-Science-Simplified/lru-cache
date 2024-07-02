<?php

namespace App\Cache;

class Node
{
    public ?Node $prev = null;
    public ?Node $next = null;

    public function __construct(
        public string $key,
        public mixed $value,
    )
    {
    }
}
