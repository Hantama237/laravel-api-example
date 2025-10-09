<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Rute Publik
Route::post('/login', [AuthController::class, 'login']);
Route::post('/login/pin', [AuthController::class, 'loginWithPin']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])
    ->middleware('throttle:6,1');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])
    ->middleware('throttle:6,1');
Route::post('/regenerate-pin', [AuthController::class, 'regeneratePin'])
    ->middleware('throttle:6,1');
Route::get('/verify-email/{id}/{hash}', [AuthController::class, 'verify'])
    ->middleware([ 'throttle:6,1'])
    ->name('verification.verify');


// Rute yang Memerlukan Otentikasi
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
});