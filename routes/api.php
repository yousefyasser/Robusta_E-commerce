<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Middleware\Admin;

Route::post('/login', [SessionController::class, 'store']);

Route::middleware([Admin::class])->group(function () {
    Route::post('/categories/create', [CategoryController::class, 'store']);
    Route::post('/products/create', [ProductController::class, 'store']);
    Route::get('/orders', [OrderController::class, 'index']);
});
