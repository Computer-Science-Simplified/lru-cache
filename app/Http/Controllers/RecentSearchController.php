<?php

namespace App\Http\Controllers;

use App\Cache\LRUCache;
use App\Http\Resources\ArtistResource;

class RecentSearchController extends Controller
{
    public function __construct(private readonly LRUCache $cache)
    {
    }

    public function index()
    {
        $artists = $this->cache->getAll(fn ($item) => unserialize($item));

        return ArtistResource::collection($artists);
    }
}
