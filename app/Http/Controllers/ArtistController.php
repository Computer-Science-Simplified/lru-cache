<?php

namespace App\Http\Controllers;

use App\Cache\LRUCache;
use App\Http\Resources\ArtistResource;
use App\Models\Artist;

class ArtistController extends Controller
{
    public function __construct(private readonly LRUCache $cache)
    {
    }

    public function show(int $id)
    {
        $artist = unserialize($this->cache->get($id));

        if (!$artist) {
            $artist = Artist::findOrFail($id);

            $this->cache->put($artist->id, serialize($artist));
        }

        return ArtistResource::make($artist);
    }
}
