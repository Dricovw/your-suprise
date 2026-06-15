<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('categoryInfo')->where('active', true)->get();
        return response()->json($products);    }

    public function show($id)
    {
        $product = Product::with('categoryInfo')->findOrFail($id);
        return response()->json($product);    }

    public function store(Request $request)
    {
        $product = Product::create($request->validated());
        return response()->json($product, 201);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->update($request->validated());
        return response()->json($product);
    }

    public function destroy($id)
    {
        Product::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}