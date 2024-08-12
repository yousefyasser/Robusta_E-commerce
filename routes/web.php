<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SessionController;

Route::post('/login', [SessionController::class, 'store']);
