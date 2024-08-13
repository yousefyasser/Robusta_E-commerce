<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

use App\Models\Product;

class ProductController extends Controller
{
    public function store(Request $request): Response
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
            return response($e->errors(), 422);
        }

        $newProduct = Product::create($request->all());
        return response($newProduct, 200);
    }
}
