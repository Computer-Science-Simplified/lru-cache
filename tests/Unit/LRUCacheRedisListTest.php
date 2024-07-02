<?php

namespace Tests\Unit;

use App\Cache\LRUCacheRedisList;
use Illuminate\Support\Facades\Redis;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LRUCacheRedisListTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Redis::flushall();
    }

    #[Test]
    public function it_should_keep_elements_in_the_right_order()
    {
        $lru = new LRUCacheRedisList(2, 'items', 'cache');

        $lru->put('first', 1);
        $lru->put('second', 2);

        $this->assertSame(1, (int) $lru->get('first'));

        $lru->put('third', 3);

        $this->assertNull($lru->get('second'));

        $lru->put('fourth', 4);

        $this->assertNull($lru->get('first'));
        $this->assertSame(3, (int) $lru->get('third'));
        $this->assertSame(4, (int) $lru->get('fourth'));
    }
}
