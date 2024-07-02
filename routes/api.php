<?php

use App\Http\Controllers\ArtistController;
use App\Http\Controllers\RecentSearchController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/artists/{artist}', [ArtistController::class, 'show']);
    Route::get('/users/{user}/recent-searches', [RecentSearchController::class, 'index']);
});
