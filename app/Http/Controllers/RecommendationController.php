<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class RecommendationController extends Controller
{
    public function show(string $cartId): JsonResponse
    {
        $items = CartItem::with('ProductInfo')
            ->where('cart_id', $cartId)
            ->get();

        if ($items->isEmpty()) {
            return response()->json(['message' => 'Cart not found or empty.'], 404);
        }

        // Build a concise product list for the prompt
        $productList = $items->map(function ($item) {
            $title    = $item->ProductInfo->title ?? 'Unknown';
            $category = $item->ProductInfo->category ?? 'Unknown';
            return "- {$title} (category: {$category})";
        })->join("\n");

        $prompt = <<<PROMPT
A customer has the following items in their cart:
{$productList}

Suggest exactly 2 complementary products they might also want to buy.
For each product return:
- name: short product name
- reason: one sentence explaining why it pairs well with something in their cart

Respond only with a JSON array, no markdown, no extra text. Example format:
[{"name":"...","reason":"..."},...]
PROMPT;

        $response = Http::withToken(config('services.openai.key'))
            ->post('https://api.openai.com/v1/chat/completions', [
                'model'      => 'gpt-4o',
                'max_tokens' => 400,
                'messages'   => [
                    ['role' => 'system', 'content' => 'You are a helpful shopping assistant. Always respond with valid JSON only.'],
                    ['role' => 'user',   'content' => $prompt],
                ],
            ]);

        if ($response->failed()) {
            return response()->json(['message' => 'Could not reach OpenAI.'], 502);
        }

        $content = $response->json('choices.0.message.content', '[]');

        $content = preg_replace('/```json|```/', '', $content);

        $recommendations = json_decode(trim($content), true) ?? [];

        return response()->json($recommendations);
    }
}