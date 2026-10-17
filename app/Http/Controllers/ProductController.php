<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Only these query-string keys may be used as filters.
    private const FILTERABLE = ['name', 'category', 'brand', 'price'];

    public function index(Request $request): JsonResponse
    {
        $query = Product::query();

        foreach (self::FILTERABLE as $field) {
            if ($request->query($field)) {
                $query->where($field, $request->query($field));
            }
        }

        return response()->json($query->get());
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'brand'    => 'required|string|max:255',
            'price'    => 'required|numeric|min:0',
            'stock'    => 'required|integer|min:0',
        ]);

        return response()->json(Product::create($data), 201);
    }

    public function show(Product $product): JsonResponse
    {
        return response()->json($product);
    }

    public function update(Request $request, Product $product): JsonResponse
    {
        $data = $request->validate([
            'name'     => 'sometimes|required|string|max:255',
            'category' => 'sometimes|required|string|max:255',
            'brand'    => 'sometimes|required|string|max:255',
            'price'    => 'sometimes|required|numeric|min:0',
            'stock'    => 'sometimes|required|integer|min:0',
        ]);

        $product->update($data);

        return response()->json($product);
    }

    public function destroy(Product $product): JsonResponse
    {
        $product->delete();

        return response()->json(null, 204);
    }
}