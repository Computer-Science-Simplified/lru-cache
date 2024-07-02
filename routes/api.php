<?php

use App\Http\Controllers\ArtistController;
use App\Http\Controllers\RecentSearchController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/artists/{id}', [ArtistController::class, 'show']);
    Route::get('/searches/recent', [RecentSearchController::class, 'index']);
});
