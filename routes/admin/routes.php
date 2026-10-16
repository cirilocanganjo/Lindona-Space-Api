<?php

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;


Route::group(['controller' => AdminController::class ], function () {
        Route::get('/admin', 'index');
});