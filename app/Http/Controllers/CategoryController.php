<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function store(CategoryRequest $request): JsonResponse
    {
        $newCategory = Category::create($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Category created successfully',
            'data' => $newCategory,
        ]);
    }
}
