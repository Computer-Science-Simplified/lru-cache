<?php

namespace App\Cache;

class LRUCacheLinkedList extends LRUCache
{
    public Node $head;

    private Node $tail;

    /** @var array<string, Node> */
    private array $map = [];

    public int $length;

    public function __construct(int $capacity)
    {
        parent::__construct($capacity);

        $this->length = 0;

        $this->head = new Node('', null);
        $this->tail = new Node('', null);

        $this->head->next = $this->tail;
        $this->head->prev = null;

        $this->tail->next = null;
        $this->tail->prev = $this->head;
    }

    public function put(string $key, mixed $value): void
    {
        if ($this->length >= $this->capacity) {
            $this->removeLeastRecentlyUsed();
        }

        $node = new Node($key, $value);

        $this->append($node);

        $this->length++;
    }

    public function get(string $key): mixed
    {
        if (!isset($this->map[$key])) {
            return null;
        }

        $node = $this->map[$key];

        $this->detach($node);

        $this->append($node);

        return $this->map[$key]->value;
    }

    private function detach(Node $node): void
    {
        $node->prev->next = $node->next;
        $node->next->prev = $node->prev;
    }

    private function append(Node $node): void
    {
        $node->next = $this->tail;
        $node->prev = $this->tail->prev;
        $node->prev->next = $node;
        $this->tail->prev = $node;

        $this->map[$node->key] = $node;
    }

    private function removeLeastRecentlyUsed(): void
    {
        $node = $this->head->next;

        $this->head->next = $node->next;
        $node->next->prev = $node->prev;

        $this->length--;

        unset($this->map[$node->key]);
    }
}
