<?php

use App\Http\Controllers\EraController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('eras', EraController::class)->only(['index', 'show']);


Route::middleware(['auth:sanctum', 'can:is-admin'])->prefix('admin')->group(function () {
    Route::apiResource('eras', EraController::class)->except(['index', 'show']);
});
