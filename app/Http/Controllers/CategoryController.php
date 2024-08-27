<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required',
                'description' => 'required',
            ]);

            if ($request->has('parent_id') && $request->parent_id) {
                $request->validate([
                    'parent_id' => 'exists:categories,id',
                ]);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }

        $newCategory = Category::create($request->only('name', 'description', 'parent_id'));

        return response()->json([
            'status' => 'success',
            'message' => 'Category created successfully',
            'data' => $newCategory,
        ]);
    }
}
