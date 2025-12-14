<?php

use App\Http\Controllers\EraController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\NewsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//// Public routes ////
// eras
Route::apiResource('eras', EraController::class)->only(['index', 'show']);
// news
Route::apiResource('news', NewsController::class)->only(['index', 'show']);
// Movies
Route::apiResource('movies', MovieController::class)->only(['index', 'show']);
Route::apiResource('movies', MovieController::class)->except(['index', 'show']);
//// Admin routes ////
Route::middleware(['auth:sanctum', 'can:is-admin'])->prefix('admin')->group(function () {
    // eras
    Route::apiResource('eras', EraController::class)->except(['index', 'show']);
    // news
    Route::apiResource('news', NewsController::class)->except(['index', 'show']);
    // Movies
});
