<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

use App\Models\Product;

class ProductController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'name' => ['required'],
                'description' => ['required'],
                'price' => ['required', 'numeric'],
                'category_id' => ['required', 'exists:categories,id'],
                'stock' => ['required', 'numeric'],
                'image_url' => ['nullable', 'url'],
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }

        $newProduct = Product::create($request->all());
        return response()->json([
            'status' => 'success',
            'message' => 'Product created successfully',
            'data' => $newProduct
        ], 200);
    }
}
