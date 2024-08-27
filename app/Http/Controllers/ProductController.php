<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use Illuminate\Http\JsonResponse;

use App\Models\Product;

class ProductController extends Controller
{
    public function store(ProductRequest $request): JsonResponse
    {
        $newProduct = Product::create($request->validated());
        return response()->json([
            'status' => 'success',
            'message' => 'Product created successfully',
            'data' => $newProduct
        ], 200);
    }
}
