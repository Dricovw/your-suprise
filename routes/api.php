<?php

use Illuminate\Support\Facades\Route;
use App\Models\Product;
use App\Models\Category;
use App\Models\CartItem;
use App\Http\Controllers\RecommendationController;

Route::get('/productinfo/{id}', function ($id) {
    $product = Product::with('categoryInfo')->findorfail($id);

    return response()->json($product);
});


Route::get('/activeproductlist', function () {
    $products = Product::with('categoryInfo')->where('active', true)->get();

    return response()->json($products);
});


Route::get('/getcart/{cartId}', function ($cartId) {
    $cartItems = CartItem::with('ProductInfo')->where('cart_id', $cartId)->get();

    return response()->json($cartItems);
});

Route::get('/getcart/{cartId}/recommendations', [RecommendationController::class, 'show']);

