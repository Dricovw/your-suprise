<?php

use Illuminate\Support\Facades\Route;
use App\Models\Product;
use App\Models\Category;
use App\Models\CartItem;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\API\ProductController;

Route::get('/productinfo/{id}', [ProductController::class, 'show']);


Route::get('/activeproductlist', [ProductController::class, 'index']);

Route::get('/getcart/{cartId}', function ($cartId) {
    $cartItems = CartItem::with('ProductInfo')->where('cart_id', $cartId)->get();

    return response()->json($cartItems);
});

Route::get('/getcart/{cartId}/recommendations', [RecommendationController::class, 'show']);



Route::get('/products', [ProductController::class, 'index']);
Route::get('/productinfo/{id}', [ProductController::class, 'show']);
Route::post('/products', [ProductController::class, 'store']);
Route::put('/products/{id}', [ProductController::class, 'update']);
Route::delete('/products/{id}', [ProductController::class, 'destroy']);
Route::get('/products', [ProductController::class, 'index']);