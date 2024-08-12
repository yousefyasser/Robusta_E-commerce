<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

final readonly class CategoryQuery
{
    /**
     * @return Collection<int, Category>
     */
    public function index(): Collection
    {
        return Category::with('subcategories')->whereNull('parent_id')->get();
    }

    /**
     * @return Collection<int, Category>
     */
    public function subcategories(Category $category): Collection
    {
        return $category->subcategories;
    }
}
