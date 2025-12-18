<?php

use App\Http\Controllers\AuditoriumController;
use App\Http\Controllers\CastMemberController;
use App\Http\Controllers\CinemaController;
use App\Http\Controllers\DirectorController;
use App\Http\Controllers\EraController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ScreeningController;
use App\Http\Controllers\UserRoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::middleware('auth:sanctum')->group(function() {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

//// Public routes ////

// Eras
Route::apiResource('eras', EraController::class)->only(['index', 'show']);
// Cinemas
Route::apiResource('cinemas', CinemaController::class)->only(['index', 'show']);
// Auditoriums
Route::apiResource('auditoriums', AuditoriumController::class)->only(['index', 'show']);
// News
Route::apiResource('news', NewsController::class)->only(['index', 'show']);
// Movies
Route::apiResource('movies', MovieController::class)->only(['index', 'show']);
Route::get('movies/{id}/similar', [MovieController::class, 'getSimilarMovies']);
// Screenings
Route::apiResource('screenings', ScreeningController::class)->only(['index', 'show']);
Route::get('screenings/{id}/seats', [ScreeningController::class, 'getScreeningSeats']);
//  Genres
Route::resource('genres', GenreController::class)->only(['index', 'show']);
// Languages
Route::apiResource('languages', LanguageController::class)->only(['index', 'show']);
// Cast Members
Route::apiResource('castMembers', CastMemberController::class)->only(['index', 'show']);
// Directors
Route::apiResource('directors', DirectorController::class)->only(['index', 'show']);

//// Admin routes ////

Route::middleware(['auth:sanctum'])->prefix('admin')->group(function () {
    // Eras
    Route::apiResource('eras', EraController::class)->except(['index', 'show']);    
    // Cinemas
    Route::apiResource('cinemas', CinemaController::class)->except(['index', 'show']);
    // Auditoriums
    Route::apiResource('auditoriums', AuditoriumController::class)->except(['index', 'show']);
    // News
    Route::apiResource('news', NewsController::class)->except(['index', 'show']);
    // Movies
    Route::apiResource('movies', MovieController::class)->except(['index', 'show']);
    // Screenings
    Route::apiResource('screenings', ScreeningController::class)->except(['index', 'show']);    
    // Genres    
    Route::resource('genres', GenreController::class)->except(['index', 'show']);
    // Languages
    Route::apiResource('languages', LanguageController::class)->except(['index', 'show']); 
    // Cast Members
    Route::apiResource('castMembers', CastMemberController::class)->except(['index', 'show']);   
    // Directors
    Route::apiResource('directors', DirectorController::class)->except(['index', 'show']);
    // Users
    Route::patch('users/{id}/role', [UserRoleController::class, 'update']);
});

require __DIR__.'/auth.php';