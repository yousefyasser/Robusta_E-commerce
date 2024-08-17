<?php

namespace Tests\Unit;

use App\Models\Category;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;
    use MakesGraphQLRequests;

    public function test_create_category_unauthorized()
    {
        $categoryData = Category::factory()->make()->toArray();

        $this->postJson('/api/categories/create', $categoryData)->assertStatus(401);

        $this->login('user');
        $this->postJson('/api/categories/create', $categoryData)->assertStatus(401);
    }

    public function test_create_category_validation()
    {
        $this->login('admin');

        $categoryData = Category::factory()->make(['parent_id' => 1])->toArray();

        $this->postJson('/api/categories/create', [])->assertStatus(422);

        $this->postJson('/api/categories/create', $categoryData)->assertStatus(422);
    }

    public function test_create_category_successfully()
    {
        $this->login('admin');

        $categoryData = Category::factory()->make()->toArray();

        $this->postJson('/api/categories/create', $categoryData)->assertStatus(200);

        $this->assertCount(1, Category::all());
    }

    public function test_get_categories()
    {
        $mainCategory = Category::factory()->create();
        $subcategory = Category::factory()->create(['parent_id' => $mainCategory->id]);

        $response = $this->graphQL('
        {
            categories {
                id
                name
                subcategories{
                    id
                    name
                    description
                }
            }
        }
        ');

        $response->assertJson([
            'data' => [
                'categories' => [
                    [
                        'id' => $mainCategory->id,
                        'name' => $mainCategory->name,
                        'subcategories' => [
                            [
                                'id' => $subcategory->id,
                                'name' => $subcategory->name,
                                'description' => $subcategory->description,
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }
}
