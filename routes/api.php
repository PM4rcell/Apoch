<?php

use App\Http\Controllers\AchievementController;
use App\Http\Controllers\AuditoriumController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BookingProductController;
use App\Http\Controllers\BookingTicketController;
use App\Http\Controllers\CastMemberController;
use App\Http\Controllers\CinemaController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DirectorController;
use App\Http\Controllers\EraController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ProductTypeController;
use App\Http\Controllers\ScreeningController;
use App\Http\Controllers\SeatController;
use App\Http\Controllers\SeatMapController;
use App\Http\Controllers\SeatTypeController;
use App\Http\Controllers\TicketTypeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserRoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



//// User Routes ////

Route::middleware('auth:sanctum')->group(function() {
    // Profile
    Route::get('/user/me', [UserController::class, 'me']);
    Route::patch('/user/me', [UserController::class, 'updateMe']);
    // Comment
    Route::post('/movies/{movie}/comments', [CommentController::class, 'store']);
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
// Route::get('screenings/{id}/seats', [ScreeningController::class, 'getScreeningSeats']);
//  Genres
Route::resource('genres', GenreController::class)->only(['index', 'show']);
// Languages
Route::apiResource('languages', LanguageController::class)->only(['index', 'show']);
// Cast Members
Route::apiResource('castMembers', CastMemberController::class)->only(['index', 'show']);
// Directors
Route::apiResource('directors', DirectorController::class)->only(['index', 'show']);
// Achievements
Route::apiResource('achievements', AchievementController::class)->only(['index', 'show']);
// Ticket Types & Tickets
Route::apiResource('ticketTypes', TicketTypeController::class)->only(['index', 'show']);
// Product Types & Products
Route::apiResource('productTypes', ProductTypeController::class)->only(['index', 'show']);
// Seats & Seat Types
Route::apiResource('seatTypes', SeatTypeController::class)->only(['index', 'show']);
route::apiResource('seats', SeatController::class)->only(['index', 'show']);
// Booking
Route::get('/screenings/{screening}/seats', [SeatMapController::class, 'index']);

Route::post('/bookings/lock', [BookingController::class, 'lockSeats']);
Route::put('/bookings/{booking}/seats', [BookingController::class, 'updateSeats']);
Route::post('/bookings/{booking}/checkout', [BookingController::class, 'checkout']);
Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel']);


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
    Route::patch('user/{id}/role', [UserController::class, 'updateRole']);
    Route::apiResource('users', UserController::class);
    // Achievements
    Route::apiResource('achievements', AchievementController::class)->except(['index', 'show']);
    // Ticket Types & Seats
    Route::apiResource('ticketTypes', TicketTypeController::class)->except(['index', 'show']);
    Route::apiResource('bookingTickets', BookingTicketController::class);
    // Product Types & Products
    Route::apiResource('productTypes', ProductTypeController::class)->except(['index', 'show']);
    Route::apiResource('bookingProducts', BookingProductController::class);
    // Seats & Seat Types
    Route::apiResource('seatTypes', SeatTypeController::class)->except(['index', 'show']);
    Route::apiResource('seats', SeatController::class)->except(['index', 'show']);    
    // Comments
    Route::apiResource('comments', CommentController::class)->except(['store']);
    // Bookings
    Route::apiResource('bookings', BookingController::class);
});

require __DIR__.'/auth.php';