<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

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
    });
