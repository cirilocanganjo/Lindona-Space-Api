<?php

use Illuminate\Support\Facades\Route;



Route::group(['prefix' => 'auth'], function () {
    Route::post('/login', [\App\Http\Controllers\Auth\AuthController::class, 'login']);
    Route::post('/logout', [\App\Http\Controllers\Auth\AuthController::class, 'logout']);
    Route::post('/register', [\App\Http\Controllers\Auth\AuthController::class, 'register']);
});



Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user(); // ou auth()->user()
});