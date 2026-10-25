<?php

use Illuminate\Support\Facades\Route;



Route::group(['prefix' => 'auth'], function () {
    Route::post('/login', [\App\Http\Controllers\Auth\AuthController::class, 'login']);
    Route::post('/logout', [\App\Http\Controllers\Auth\AuthController::class, 'logout']);
    Route::post('/register', [\App\Http\Controllers\Auth\AuthController::class, 'register']);
    
    
    Route::middleware(['auth:api'])->group(function () {
        Route::get('/user', [\App\Http\Controllers\Auth\AuthController::class, 'getAuthenticatedUser']);
        Route::put('/user', [\App\Http\Controllers\Auth\AuthController::class, 'updateAuthenticatedUser']);
    });
});


