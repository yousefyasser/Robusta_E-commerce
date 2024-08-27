<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Middleware\AllowAdmin;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware([AllowAdmin::class])->group(function () {
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::get('/orders', [OrderController::class, 'index']);
});
