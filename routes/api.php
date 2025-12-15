<?php

use App\Http\Controllers\EraController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ScreeningController;
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
// Screenings
Route::apiResource('screenings', ScreeningController::class)->only(['index', 'show']);
Route::get('screenings/{id}/seats', [ScreeningController::class, 'getScreeningSeats']);
//// Admin routes ////
Route::middleware(['auth:sanctum', 'can:is-admin'])->prefix('admin')->group(function () {
    // eras
    Route::apiResource('eras', EraController::class)->except(['index', 'show']);
    // news
    Route::apiResource('news', NewsController::class)->except(['index', 'show']);
    // Movies
    Route::apiResource('movies', MovieController::class)->except(['index', 'show']);
    // Screenings
    Route::apiResource('screenings', ScreeningController::class)->except(['index', 'show']);    
});
