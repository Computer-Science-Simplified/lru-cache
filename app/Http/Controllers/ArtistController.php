<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArtistResource;
use App\Models\Artist;

class ArtistController extends Controller
{
    public function show(Artist $artist)
    {
        return ArtistResource::make($artist);
    }
}
