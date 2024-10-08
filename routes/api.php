<?php

use App\Http\Controllers\Apis\Auth\AuthController;
use App\Http\Controllers\Apis\OrderController;
use App\Http\Controllers\Apis\ProductController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => config('api.version').'/'
], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::get('products', [ProductController::class, 'index']);

    Route::group([
        'middleware' => 'jwt.auth',
    ], function () {
        Route::group([
            'middleware' => 'role:admin',
            'prefix' => 'products',
        ], function () {
            Route::post('', [ProductController::class, 'store']);
            Route::put('/{product}', [ProductController::class, 'update']);
        });

        Route::group([
            'middleware' => 'role:user',
            'prefix' => 'orders',
        ], function () {
            Route::get('', [OrderController::class, 'index']);
            Route::post('', [OrderController::class, 'store']);
        });

        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::post('logout', [AuthController::class, 'logout']);
    });

});

