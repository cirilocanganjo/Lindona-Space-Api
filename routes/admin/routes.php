<?php

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => 'auth:api' ,'prefix' =>'admin', 'controller' => AdminController::class ], function () {
        Route::get('/employee', 'index');
        Route::post('/employee', 'store');
        Route::get('/employee/{id}', 'show');
        Route::get('/employee/{id}', 'edit');
        Route::put('/employee/{id}', 'update');
        Route::delete('/employee/{id}', 'delete');
        Route::put('/change/user/status/{id}', 'changeUserStatus');

        Route::get('/all/employees/counter','getAllEmployeesCounter');
        Route::get('/all/active/employees/counter','getAllActiveEmployeesCounter');
        Route::get('/all/inactive/employees/counter','getAllInactiveEmployeesCounter');
});