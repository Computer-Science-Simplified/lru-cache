<?php

namespace Tests\Unit;

use App\Cache\LRUCacheLinkedList;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LRUCacheLinkedListTest extends TestCase
{
    #[Test]
    public function it_should_keep_elements_in_the_right_order()
    {
        $lru = new LRUCacheLinkedList(2);

        $lru->put('first', 1);
        $lru->put('second', 2);

        $this->assertSame(1, $lru->get('first'));

        $lru->put('third', 3);

        $this->assertNull($lru->get('second'));

        $lru->put('fourth', 4);

        $this->assertNull($lru->get('first'));
        $this->assertSame(3, $lru->get('third'));
        $this->assertSame(4, $lru->get('fourth'));
    }
}
