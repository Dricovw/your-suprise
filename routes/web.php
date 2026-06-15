<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;


Route::get('/', fn() => view('cart'));

Route::get('/cart/{cartId}', [CartController::class, 'show']);

Route::get('/db-overview', [App\Http\Controllers\DbOverviewController::class, 'index']);
