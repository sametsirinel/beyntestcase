<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrderController;

Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:api')
    ->group(function () {
        Route::controller(AuthController::class)
            ->prefix("auth")
            ->group(function () {
                Route::post('logout', 'logout');
                Route::post('refresh', 'refresh');
                Route::post('me', 'me');
            });

        Route::controller(OrderController::class)
            ->prefix("orders")
            ->group(function () {
                Route::post('/', 'store');
                Route::get('/', 'index');
                Route::get('/{userOrder}', 'show');
                Route::put('/{userOrder}', 'update');
            });
    });
