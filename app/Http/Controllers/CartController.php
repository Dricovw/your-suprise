<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use Illuminate\Http\JsonResponse;

class CartController extends Controller
{
    public function show(string $cartId): JsonResponse
    {
        $items = CartItem::with('ProductInfo')
            ->where('cart_id', $cartId)
            ->get();

        if ($items->isEmpty()) {
            return response()->json(['message' => 'Cart not found or empty.'], 404);
        }

        return response()->json($items);
    }
}